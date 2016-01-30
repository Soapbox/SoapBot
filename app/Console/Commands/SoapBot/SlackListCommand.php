<?php

namespace App\Console\Commands\SoapBot;

use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SlackListCommand extends ListCommand
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->setDecorated(false);
        $output->writeln('```');
        parent::execute($input, $output);
        $output->writeln('```');
    }
}
