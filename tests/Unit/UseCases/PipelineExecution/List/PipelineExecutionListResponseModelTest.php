<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineExecution\List;

use Illuminate\Support\Collection;
use ConsulConfigManager\Tasks\Test\TestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use ConsulConfigManager\Tasks\UseCases\PipelineExecution\List\PipelineExecutionListResponseModel;

/**
 * Class PipelineExecutionListResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineExecution\List
 */
class PipelineExecutionListResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfCollectionIsReturnedFromArray(): void
    {
        $instance = new PipelineExecutionListResponseModel([]);
        $this->assertInstanceOf(Collection::class, $instance->getEntities());
    }

    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfCollectionIsReturnedFromCollection(): void
    {
        $instance = new PipelineExecutionListResponseModel(new Collection());
        $this->assertInstanceOf(Collection::class, $instance->getEntities());
    }

    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfCollectionIsReturnedFromEloquentCollection(): void
    {
        $instance = new PipelineExecutionListResponseModel(new EloquentCollection());
        $this->assertInstanceOf(EloquentCollection::class, $instance->getEntities());
    }
}
