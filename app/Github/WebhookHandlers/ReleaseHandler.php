<?php

namespace App\Github\WebhookHandlers;

use Illuminate\Http\Request;
use App\Console\GithubKernel;
use Laravel\Lumen\Application;
use App\Entities\Github\Release;

class ReleaseHandler
{
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    private function handlePublish(Request $request)
    {
        $kernel  = new GithubKernel($this->application);
        $release = new Release($request);

        $parameters = $release->getName();
        $kernel->callWithStringArgs('changelog', $parameters);

        return $kernel->output();
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
