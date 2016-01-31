<?php

namespace App\Github\WebhookHandlers;

use App\Jobs\ReleaseJob;
use Illuminate\Http\Request;
use App\Console\GithubKernel;
use Laravel\Lumen\Application;
use App\Entities\Github\Release;
use Laravel\Lumen\Routing\DispatchesJobs;

class ReleaseHandler
{
    use DispatchesJobs;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    private function handlePublish(Request $request)
    {
        $kernel  = new GithubKernel($this->application);
        $release = new Release($request);
        $job = new ReleaseJob($release);

        $this->dispatch($job);

        return;
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
