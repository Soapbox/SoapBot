<?php

namespace App\Jobs;

use App\Jobs\Job;
use Maknz\Slack\Client;
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

    private function detected()
    {
        $this
            ->slack
            ->to($this->channel)
            ->attach([
                "fallback" => "Release {$this->release->getName()} Detected",
                "title" => "Release {$this->release->getName()} Detected",
                "title_link" => $this->release->getLink(),
                "author_name" => $this->release->getAuthorName(),
                "author_link" => $this->release->getAuthorLink(),
                "author_icon" => $this->release->getAuthorIcon(),
                "color" => "danger"
            ])
            ->send();
    }

    private function build()
    {
        // $this
        //     ->slack
        //     ->to($this->channel)
        //     ->attach([

        //     ])
        //     ->send();
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
        $this->build();
        $end = microtime(true);

        $time = date("H:i:s", $end - $start);

        $this
            ->slack
            ->to($this->channel)
            ->attach([
                "author_name" => $this->release->getAuthorName(),
                "author_link" => $this->release->getAuthorLink(),
                "author_icon" => $this->release->getAuthorIcon(),
                "fallback" => "Completed deployment in {$time}",
                "text" => "Completed deployment in {$time}",
                "color" => "danger"
            ])
            ->send();
    }
}
