<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateActionHostsTable
 */
class CreateActionHostsTable extends Migration
{
    /**
     * Table name
     * @var string
     */
    private string $tableName = 'action_hosts';

    /**
     * Run the migrations.
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table): void {
            $table->unsignedBigInteger('action_uuid');
            $table->unsignedBigInteger('service_id');

            $table->foreign('action_uuid')
                ->references('uuid')
                ->on('actions')
                ->onDelete('cascade');

            $table->foreign('service_id')
                ->references('id')
                ->on('consul_services')
                ->onDelete('cascade');

            $table->primary([
                'action_uuid',
                'service_id',
            ], $this->tableName . '_action_uuid_service_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
}
