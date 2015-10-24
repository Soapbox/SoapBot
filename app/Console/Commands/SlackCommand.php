<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

abstract class SlackCommand extends Command
{
	private $formatter;

	private function getFormatter()
	{
		if (is_null($this->formatter)) {
			$formatter = sprintf('App\Console\Formatters\%sFormatter', Str::studly($this->option('format')));
			$this->formatter = new $formatter();
		}

		return $this->formatter;
	}

	public function line($message)
	{
		parent::line($this->getFormatter()->format($message));
	}

	public function info($message)
	{
		$this->line(sprintf('<info>%s</info>', $message));
	}

	public function comment($message)
	{
		$this->line(sprintf('<comment>%s</comment>', $message));
	}

	public function error($string)
	{
		$this->line("<error>$string</error>");
	}

	protected function specifyParameters()
	{
		$this->addOption('format', null, InputOption::VALUE_OPTIONAL, '', 'cli');

		parent::specifyParameters();
	}
}
