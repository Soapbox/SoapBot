<?php

namespace App\Http\Controllers;

use App\Entities\Slack\SlashCommandEntity;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SlackController extends BaseController
{
	public function processCommand(Request $request)
	{
		$command = new SlashCommandEntity($request);

		$commandClass = sprintf('App\CommandHandlers\%sSlackCommandHandler', Str::studly($command->getCommand()));
		if (!class_exists($commandClass)) {
			return 'Error';
		}

		$handler = new $commandClass();
		return $handler->processCommand($command);
	}
}
