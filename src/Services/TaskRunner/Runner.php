<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use ConsulConfigManager\Tasks\Models\TaskExecution;
use ConsulConfigManager\Tasks\Models\ActionExecution;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use ConsulConfigManager\Tasks\Models\ActionExecutionLog;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class Runner
 * @package ConsulConfigManager\Tasks\Services\TaskRunner
 */
class Runner extends LoggableClass
{
    /**
     * Pipeline identifier reference
     * @var string|int
     */
    private string|int $pipelineIdentifier;

    /**
     * Runner constructor.
     * @param string|int $pipelineIdentifier
     * @param bool $truncate
     * @return void
     */
    public function __construct(string|int $pipelineIdentifier, bool $truncate = false)
    {
        $this->truncate($truncate);
        $this->pipelineIdentifier = $pipelineIdentifier;
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        $this->setDebug(env('TASK_RUNNER_ENABLE_DEBUG', false));
    }

    /**
     * Start runner
     * @return void
     * @throws BindingResolutionException
     */
    public function run(): void
    {
        $resolver = new Resolver($this->pipelineIdentifier);
        $resolver->setDebug($this->getDebug())
            ->setOutputInterface($this->getOutputInterface())
            ->bootstrap();

        $handler = new Handler($resolver->getPipelineEntity());
        $handler
            ->setDebug($this->getDebug())
            ->setOutputInterface($this->getOutputInterface())
            ->bootstrap();
        $handler->run();
    }

    /**
     * Clean database tables related to runner.
     * This should only be used for development/testing.
     * @param bool $truncate
     * @return void
     */
    private function truncate(bool $truncate = false): void
    {
        if ($truncate) {
            $models = [
                ActionExecutionLog::class,
                ActionExecution::class,
                TaskExecution::class,
                PipelineExecution::class,
            ];

            foreach ($models as $modelClass) {
                /**
                 * @var Model $model
                 */
                $model = new $modelClass();
                DB::table($model->getTable())->truncate();
            }
        }
    }
}
