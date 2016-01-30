<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller as BaseController;

class GithubController extends BaseController
{
    private function isSignatureValid(Request $request)
    {
        $secret = env('GITHUB_SECRET', '');
        $signature = $request->header('X-Hub-Signature');

        list($algorithm, $hash) = explode('=', $signature);

        $payload = $request->getContent();
        $payloadHash = hash_hmac($algorithm, $payload, $secret);

        return $hash === $payloadHash;
    }

    public function processWebhook(Request $request)
    {
        if (!$this->isSignatureValid($request)) {
            Log::error('There are hackers amoung us, unexpected signature on request. Alternatively ensure your GITHUB_SECRET environment value matches the secret provided to GitHub.');
        }

        $className = Str::studly($request->header('X-Github-Event'));

        $webhookHandler = sprintf('App\Github\WebhookHandlers\%sHandler', $className);

        if (!class_exists($webhookHandler)) {
            return 'Error';
        }

        $handler = new $webhookHandler();
        return $handler->handle($request);
    }
}
