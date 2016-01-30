<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class GithubController extends BaseController
{
    public function processWebhook(Request $request)
    {
        $className = Str::studly($request->header('X-Github-Event'));

        $webhookHandler = sprintf('App\Github\WebhookHandlers\%sHandler', $className);

        if (!class_exists($webhookHandler)) {
            return 'Error';
        }

        $handler = new $webhookHandler();
        return $handler->handle($request);
    }
}
