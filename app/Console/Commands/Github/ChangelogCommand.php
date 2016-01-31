<?php

namespace App\Console\Commands\Github;

use GuzzleHttp\Client;
use App\Console\Commands\SlackCommand;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputArgument;

class ChangelogCommand extends SlackCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'changelog';

    public function fire()
    {
        $released = $this->argument('released');

        // $client = new Client();

        // $response = $client->get('https://api.github.com/repos/SoapBox/soapbox-v4/releases/latest',
        //     ['query' => [
        //         'access_token' => '89d2ccb87582cce241e68d472e569a32f3bcf48b'
        //     ]]
        // );

        // $latest = json_decode($response->getBody());
        // $latest = $latest->name;
        //
        $released = '4.2.4-build.324';
        $current = '4.2.5-build.333';

        $command = sprintf('cd /home/vagrant/Development/soapbox/soapbox-v4 && raven generate-changelog %s %s', $released, $current);
        $command = "cd /home/vagrant/Development/soapbox/soapbox-v4 && /vagrant/bin/build-fe";

        $process = new Process($command);

        $process->mustRun();

        \Log::info($process->getOutput());
    }

    protected function getArguments()
    {
        return [
            ['released', InputArgument::REQUIRED, 'The currently released name'],
        ];
    }
}
