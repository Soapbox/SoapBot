<?php

namespace App\Github\WebhookHandlers;

use Illuminate\Http\Request;
use App\Interfaces\RequestHandler;

class ReleaseHandler implements RequestHandler {
    private function handlePublish(Request $request)
    {
        \Log::info($request);
    }

    public function handle(Request $request)
    {
        switch ($request->get('action'))
        {
            case 'published':
                return $this->handlePublish($request);
            default:
                return 'Error';
        }
    }
}
