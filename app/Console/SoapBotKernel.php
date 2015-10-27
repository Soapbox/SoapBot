<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\StringInput;

class SoapBotKernel extends ConsoleKernel
{
	private $output;
	private $artisanConfigured = false;

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\SoapBot\PrInfoCommand',
		'App\Console\Commands\SoapBot\SlackHelpCommand',
		'App\Console\Commands\SoapBot\SlackListCommand',
	];

	protected $includeDefaultCommands = false;

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		//
	}

	public function callWithStringArgs($command, $parameters = '')
	{
		$input = new StringInput(sprintf('%s %s', $command, $parameters));
		// $input = new \Symfony\Component\Console\Input\StringInput('pr-info 3902 --env slack --help');

		if (preg_match('/^(.*\s)?--help(\s.*)?$/', $parameters)) {

		}

		$command = $this->getArtisan()->find($command);
		$this->output = new BufferedOutput();
		$this->output->setDecorated(true);
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
			//TEMP - Move to environment file
			$artisan->setVersion('1.0.0-1');
		}
		return $artisan;
	}
}
