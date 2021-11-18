<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Models;

use Illuminate\Support\Arr;
use ConsulConfigManager\Tasks\Interfaces\ActionHostInterface;

/**
 * Class ActionHostTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Models
 */
class ActionHostTest extends AbstractModelTest
{
    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetActionUuidMethod(array $data): void
    {
        $response = $this->model($data)->getActionUuid();
        $this->assertEquals(Arr::get($data, 'action_uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetActionUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setActionUuid('0e013a2b-03f6-404d-b8f6-fd186191c145');
        $this->assertEquals('0e013a2b-03f6-404d-b8f6-fd186191c145', $model->getActionUuid());
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromGetServiceUuidMethod(array $data): void
    {
        $response = $this->model($data)->getServiceUuid();
        $this->assertEquals(Arr::get($data, 'service_uuid'), $response);
    }

    /**
     * @param array $data
     *
     * @dataProvider modelDataProvider
     * @return void
     */
    public function testShouldPassIfValidDataReturnedFromSetServiceUuidMethod(array $data): void
    {
        $model = $this->model($data);
        $model->setServiceUuid('0e013a2b-03f6-404d-b8f6-fd186191c145');
        $this->assertEquals('0e013a2b-03f6-404d-b8f6-fd186191c145', $model->getServiceUuid());
    }

    /**
     * Model data provider
     * @return \string[][][]
     */
    public function modelDataProvider(): array
    {
        return $this->actionHostModelDataProvider();
    }

    /**
     * Create model instance
     * @param array $data
     * @return ActionHostInterface
     */
    private function model(array $data): ActionHostInterface
    {
        return $this->actionHostModel($data);
    }
}
