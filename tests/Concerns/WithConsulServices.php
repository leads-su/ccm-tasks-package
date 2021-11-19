<?php

namespace ConsulConfigManager\Tasks\Test\Concerns;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Trait WithConsulServices
 * @package ConsulConfigManager\Tasks\Test\Concerns
 */
trait WithConsulServices
{
    /**
     * Create `consul_services` table
     * @return void
     */
    private function createConsulServicesTable(): void
    {
        Schema::create('consul_services', static function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('identifier');
            $table->string('service');
            $table->string('address');
            $table->integer('port');
            $table->string('datacenter');
            $table->json('tags');
            $table->json('meta');
            $table->boolean('online')->default(false);
            $table->string('environment')->default('development');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Create consul services specific tables
     * @return void
     */
    public function createConsulServicesTables(): void
    {
        $this->createConsulServicesTable();
    }

    /**
     * Drop `consul_services` table
     * @return void
     */
    private function dropConsulServicesTable(): void
    {
        Schema::dropIfExists('consul_services');
    }

    /**
     * Drop consul services specific tables
     * @return void
     */
    public function dropConsulServicesTables(): void
    {
        $this->dropConsulServicesTable();
    }
}
