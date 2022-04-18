<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineHistory\List;

use Illuminate\Support\Collection;
use ConsulConfigManager\Tasks\Test\TestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use ConsulConfigManager\Tasks\UseCases\PipelineHistory\List\PipelineHistoryListResponseModel;

/**
 * Class PipelineHistoryListResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\PipelineHistory\List
 */
class PipelineHistoryListResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfCollectionIsReturnedFromArray(): void
    {
        $instance = new PipelineHistoryListResponseModel([]);
        $this->assertInstanceOf(Collection::class, $instance->getEntities());
    }

    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfCollectionIsReturnedFromCollection(): void
    {
        $instance = new PipelineHistoryListResponseModel(new Collection());
        $this->assertInstanceOf(Collection::class, $instance->getEntities());
    }

    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfCollectionIsReturnedFromEloquentCollection(): void
    {
        $instance = new PipelineHistoryListResponseModel(new EloquentCollection());
        $this->assertInstanceOf(EloquentCollection::class, $instance->getEntities());
    }
}
