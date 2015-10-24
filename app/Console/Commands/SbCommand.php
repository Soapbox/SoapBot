<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SbCommand extends SlackCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    private $commands = [

    ];

    public function __construct()
    {
        parent::__construct();
        $this->loadCommands();
    }

    private function loadCommands()
    {
        $commands = [];
        foreach ($this->commands as $command) {
            $instance = new $command();
            $commands[$instance->getName()] = $instance;
        }
        $this->commands = $commands;
    }

    private function printAvailableCommands()
    {
        $this->line(sprintf('<info>%s</info> version <comment>%s</comment>', 'SoapBot', '1.0.0'));
        $this->line('');
        $this->info('Available Commands');
        $commands = [];
        $maxNameLength = 0;
        foreach ($this->commands as $command) {
            $name = $command->getName();
            if (strlen($name) > $maxNameLength) {
                $maxNameLength = $strlen($name);
            }

            $commands[$name] = $command->getDescription();
        }

        foreach ($commands as $name => $desciption) {
            $this->line(sprintf('    %s%s   %s', $name, str_repeat(' ', $maxNameLength - strlen($name)), $desciption));
        }
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->loadCommands();

        if (is_null($command = $this->argument('command_name'))) {
            $this->printAvailableCommands();
        }

        if (!array_key_exists($command, $this->commands))
        {

        }

        $instance = $this->commands[$command];

        $arguments['command'] = $command;

        return $instance->run(new ArrayInput($arguments), $this->output);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['format', null, InputOption::VALUE_OPTIONAL, '', 'cli'],
        ];
    }

    protected function getArguments()
    {
        return [
            ['command_name', InputArgument::OPTIONAL, 'The command to run'],
        ];
    }
}
