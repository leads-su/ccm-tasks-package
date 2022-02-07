<?php

namespace ConsulConfigManager\Tasks\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ConsulConfigManager\Tasks\Services\TaskRunner\Runner;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class PipelineRunnerJob
 * @package ConsulConfigManager\Tasks\Jobs
 */
class PipelineRunnerJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Pipeline identifier reference
     * @var string
     */
    protected string $pipelineIdentifier;

    /**
     * PipelineRunnerJob constructor.
     * @return void
     */
    public function __construct(string $pipelineIdentifier)
    {
        $this->queue = 'default_long';
        $this->pipelineIdentifier = $pipelineIdentifier;
    }

    /**
     * Execute the job.
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        $manager = new Runner($this->pipelineIdentifier);
        $manager->bootstrap();
        $manager->run();
    }
}
