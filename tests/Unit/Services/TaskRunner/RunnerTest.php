<?php

namespace ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner;

use ConsulConfigManager\Tasks\Test\TestCase;
use ConsulConfigManager\Tasks\Services\TaskRunner\Runner;

/**
 * Class RunnerTest
 * @package ConsulConfigManager\Tasks\Test\Unit\Services\TaskRunner
 */
class RunnerTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInstanceCanBeCreated(): void
    {
        $instance = $this->createInstance();
        $this->assertInstanceOf(Runner::class, $instance);
    }

    /**
     * Create new runner instance
     * @return Runner
     */
    private function createInstance(): Runner
    {
        $instance = new Runner('ec019e70-ec3d-4ecd-b744-7870b4fbc7a6', false);
        $instance->bootstrap();
        return $instance;
    }
}
