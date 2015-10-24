<?php

namespace App\CommandHandlers;

use App\Entities\Slack\SlashCommandEntity;
use Illuminate\Support\Str;

class SbSlackCommandHandler
{
	public function processCommand(SlashCommandEntity $command)
	{
		$args = explode(' ', $command->getText(), 2);
		$commandString = $args[0];
		$text = '';

		if (count($args) == 2 && !empty($args[1])) {
			$text = $args[1];
		}

		$commandClass = sprintf('App\SoapBot\Commands\%sCommand', Str::studly($commandString));

		if (!class_exists($commandClass)) {
			return 'Error';
		}

		$sbCommand = new $commandClass();
		return $sbCommand->execute($text);
	}
}
