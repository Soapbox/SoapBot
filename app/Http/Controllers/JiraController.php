<?php

namespace App\Http\Controllers;

use App\Entities\Slack\SlashCommandEntity;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JiraController extends BaseController
{
	public function processWebhook(Request $request)
	{
		$commandClass = sprintf('App\Jira\WebhookHandlers\%sHandler', Str::studly(str_replace('jira:', '', $request->get('webhookEvent'))));
		if (!class_exists($commandClass)) {
			return 'Error';
		}

		$handler = new $commandClass();
		return $handler->handleWebhook($request);
	}
}
