<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Models;

use ConsulConfigManager\Tasks\Interfaces\PipelineExecutionInterface;

/**
 * Class PipelineExecutionTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
class PipelineExecutionTest extends AbstractModelTest
{
    /**
     * Model data provider
     * @return \string[][][]
     */
    public function modelDataProvider(): array
    {
        return $this->pipelineExecutionModelDataProvider();
    }

    /**
     * Create model instance
     * @param array $data
     * @return PipelineExecutionInterface
     */
    private function model(array $data): PipelineExecutionInterface
    {
        return $this->pipelineExecutionModel($data);
    }
}
