<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use ConsulConfigManager\Tasks\Models\TaskExecution;
use ConsulConfigManager\Tasks\Models\ActionExecution;
use Symfony\Component\Console\Output\OutputInterface;
use ConsulConfigManager\Tasks\Models\PipelineExecution;
use ConsulConfigManager\Tasks\Models\ActionExecutionLog;

/**
 * Class Runner
 * @package ConsulConfigManager\Tasks\Services\TaskRunner
 */
class Runner
{
    /**
     * Pipeline identifier reference
     * @var string
     */
    private string|int $pipelineIdentifier;

    /**
     * Output interface instance
     * @var OutputInterface|null
     */
    private ?OutputInterface $output = null;

    /**
     * Indicates whether debugging is enabled
     * @var bool
     */
    private bool $debug = false;

    /**
     * Runner constructor.
     * @param string|int $pipelineIdentifier
     * @param bool $withTruncate
     * @return void
     */
    public function __construct(string|int $pipelineIdentifier, bool $withTruncate = false)
    {
        $this->truncate($withTruncate);
        $this->pipelineIdentifier = $pipelineIdentifier;
        $this->debug = env('TASK_RUNNER_ENABLE_DEBUG', false);
    }

    /**
     * Set output interface for debugging
     * @param OutputInterface|null $output
     * @return $this
     */
    public function setOutputInterface(?OutputInterface $output): Runner
    {
        $this->output = $output;
        return $this;
    }

    /**
     * Start runner
     * @return void
     */
    public function run(): void
    {
    }

    /**
     * Clean execution tables.
     * This should only be used for development/testing
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
