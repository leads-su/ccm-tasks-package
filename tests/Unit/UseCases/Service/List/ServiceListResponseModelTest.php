<?php

namespace ConsulConfigManager\Tasks\Test\Unit\UseCases\Service\List;

use Illuminate\Support\Collection;
use ConsulConfigManager\Tasks\Test\TestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use ConsulConfigManager\Tasks\UseCases\Service\List\ServiceListResponseModel;

/**
 * Class ServiceListResponseModelTest
 * @package ConsulConfigManager\Tasks\Test\Unit\UseCases\Service\List
 */
class ServiceListResponseModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfCollectionIsReturnedFromArray(): void
    {
        $instance = new ServiceListResponseModel([]);
        $this->assertInstanceOf(Collection::class, $instance->getEntities());
    }

    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfCollectionIsReturnedFromCollection(): void
    {
        $instance = new ServiceListResponseModel(new Collection());
        $this->assertInstanceOf(Collection::class, $instance->getEntities());
    }

    /**
     * @return void
     */
    public function testShouldPassIfInstanceOfCollectionIsReturnedFromEloquentCollection(): void
    {
        $instance = new ServiceListResponseModel(new EloquentCollection());
        $this->assertInstanceOf(EloquentCollection::class, $instance->getEntities());
    }
}
