<?php

namespace App\Console;

use Symfony\Component\Console\Input\StringInput;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Symfony\Component\Console\Output\BufferedOutput;

class GithubKernel extends ConsoleKernel
{
    private $output;

    private $artisanConfigured = false;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Github\ChangelogCommand',
    ];

    protected $includeDefaultCommands = false;

    public function callWithStringArgs($command, $parameters = '')
    {
        $input = new StringInput(sprintf('%s %s', $command, $parameters));

        $command = $this->getArtisan()->find($command);
        $this->output = new BufferedOutput();
        $this->output->setDecorated(false);
        $this->getArtisan()->run($input, $this->output);
    }

    public function output()
    {
        return $this->output ? $this->output->fetch() : '';
    }

    protected function getArtisan()
    {
        $artisan = parent::getArtisan();
        if (!$this->artisanConfigured) {
            $artisan->setName('SoapBot');
            $artisan->setVersion(SoapBot_Version);
        }
        return $artisan;
    }
}
