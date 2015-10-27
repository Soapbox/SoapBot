<?php

namespace App\CommandHandlers;

use App\Console\SoapBotKernel;
use App\Entities\Slack\SlashCommandEntity;
use Illuminate\Support\Str;
use Laravel\Lumen\Application;

class SbSlackCommandHandler
{
	public function processCommand(SlashCommandEntity $command, Application $app)
	{
		$args = explode(' ', $command->getText(), 2);
		$command = $args[0];
		if (empty(trim($command))) {
			$command = 'list';
		}
		$parameters = '';

		if (count($args) == 2 && !empty($args[1])) {
			$parameters = $args[1];
		}

		$kernel = new SoapBotKernel($app);
		$kernel->callWithStringArgs($command, $parameters);
		return $kernel->output();

		// $commandClass = sprintf('App\SoapBot\Commands\%sCommand', Str::studly($commandString));

		// if (!class_exists($commandClass)) {
		// 	return 'Error';
		// }

		// $sbCommand = new $commandClass();
		// return $sbCommand->execute($text);
	}
}
