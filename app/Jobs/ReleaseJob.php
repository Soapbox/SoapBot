<?php

namespace App\Jobs;

use App\Jobs\Job;
use Maknz\Slack\Client;
use Maknz\Slack\Attachment;
use App\Entities\Github\Release;
use Symfony\Component\Process\Process;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReleaseJob extends Job implements SelfHandling, ShouldQueue
{
    private $slack;
    private $channel;
    private $release;

    public function __construct(Release $release)
    {
        $this->release = $release;
    }

    private function sendAttachment(array $attachment)
    {
        $this
            ->slack
            ->to($this->channel)
            ->attach($attachment)
            ->send();
    }

    private function sendMessage($time, $title, $message)
    {
        $time = date("H:i:s", $time);
        $this
            ->slack
            ->to($this->channel)
            ->send("*{$time}*: _{$title}:_ {$message}");
    }

    private function detected()
    {
        $this->slack
            ->to($this->channel)
            ->send("*{$this->release->getRepository()}:* _{$this->release->getAuthorName()}_ has built a new release ({$this->release->getName()}).");
    }

    private function buildFrontend($start)
    {
        $this->sendMessage(microtime(true) - $start, "running task", "downloading bower dependencies");
        $command = "cd /home/vagrant/Development/soapbox/soapbox-v4 && /vagrant/bin/build-fe";
        $process = new Process($command);
        $process->setTimeout(3600);
        $process->run(function ($type, $buffer) use ($start) {
            $output = [];
            if (1 === preg_match("/Running(.*)task/", $buffer, $output)) {
                $this->sendMessage(microtime(true) - $start, "running task", $output[1]);
            }
        });
    }

    private function buildBackend($start)
    {
        $this->sendMessage(microtime(true) - $start, "running", "composer");
        $command = "export HOME=/home/vagrant && cd /home/vagrant/Development/soapbox/soapbox-v4 && /usr/local/bin/composer install";
        $process = new Process($command);
        $process->setTimeout(3600);
        $process->mustRun();
        $this->sendMessage(microtime(true) - $start, "finished", "composer");
    }

    private function tar($start)
    {
        $this->sendMessage(microtime(true) - $start, "archiving", $this->release->getName());
        $command = "cd /home/vagrant/Development/soapbox/ && tar -cf /home/vagrant/{$this->release->getRepository()}-{$this->release->getName()}.tar -X ./soapbox-v4/.tarignore ./soapbox-v4";
        $process = new Process($command);
        $process->setTimeout(3600);
        $process->mustRun();
        $this->sendMessage(microtime(true) - $start, "archiving", "done");
    }

    public function handle()
    {
        $settings = [
            'username' => env('SLACK_USERNAME', ''),
            'link_names' => true
        ];

        $this->channel = env('SLACK_CHANNEL', '');
        $this->slack = new Client(env('SLACK_WEBHOOK', ''), $settings);

        $start = microtime(true);
        $this->detected();
        $this->buildFrontend($start);
        $this->buildBackend($start);
        $this->tar($start);
        $end = microtime(true);

        $time = date("H:i:s", $end - $start);

        $this->sendAttachment([
            "author_name" => $this->release->getAuthorName(),
            "author_link" => $this->release->getAuthorLink(),
            "author_icon" => $this->release->getAuthorIcon(),
            "fallback" => "Completed deployment in {$time}",
            "text" => "Completed deployment in {$time}",
            "color" => "danger"
        ]);
    }
}
