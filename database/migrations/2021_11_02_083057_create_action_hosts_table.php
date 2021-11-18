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
            $table->foreignUuid('action_uuid');
            $table->foreignUuid('service_uuid');

            $table->primary([
                'action_uuid',
                'service_uuid',
            ], $this->tableName . '_action_uuid_service_uuid_primary');
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
