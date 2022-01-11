<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateActionExecutionsTable
 */
class CreateActionExecutionsTable extends Migration
{
    /**
     * Table name
     * @var string
     */
    private string $tableName = 'action_executions';

    /**
     * Run the migrations.
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('server_uuid');
            $table->foreignUuid('action_uuid');
            $table->foreignUuid('task_uuid');
            $table->foreignUuid('pipeline_uuid');
            $table->foreignUuid('pipeline_execution_uuid');
            /**
             * @see \ConsulConfigManager\Tasks\Enums\ExecutionState
             */
            $table->integer('state')->default(0);
            $table->timestamps();
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
