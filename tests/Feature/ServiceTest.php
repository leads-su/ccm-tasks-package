<?php

namespace ConsulConfigManager\Tasks\Test\Feature;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class ServiceTest
 * @package ConsulConfigManager\Tasks\Test\Feature
 */
class ServiceTest extends AbstractFeatureTest
{
    /**
     * @return void
     */
    public function testShouldPassIfEmptyListIsReturned(): void
    {
        $response = $this->get('/task-manager/services');
        $response->assertStatus(200);
        $this->assertEmpty($response->json('data'));
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function testShouldPassIfNonEmptyListReturned(): void
    {
        $service = $this->createServiceThroughRepository();
        $response = $this->get('/task-manager/services');
        $response->assertStatus(200);
        $data = Arr::first($response->json('data'));
        $this->assertSame($service->getService(), Arr::get($data, 'service'));
        $this->assertSame($service->getIdentifier(), Arr::get($data, 'identifier'));
        $this->assertSame($service->getAddress(), Arr::get($data, 'address'));
        $this->assertSame($service->getPort(), Arr::get($data, 'port'));
        $this->assertSame($service->getEnvironment(), Arr::get($data, 'environment'));
    }
}
