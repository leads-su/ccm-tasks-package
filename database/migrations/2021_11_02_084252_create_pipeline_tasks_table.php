<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePipelineTasksTable
 */
class CreatePipelineTasksTable extends Migration
{
    /**
     * Table name
     * @var string
     */
    private string $tableName = 'pipeline_tasks';

    /**
     * Run the migrations.
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table): void {
            $table->foreignUuid('pipeline_uuid');
            $table->foreignUuid('task_uuid');
            $table->integer('order');

            $table->primary([
                'pipeline_uuid',
                'task_uuid',
            ], 'pipeline_tasks_pipeline_uuid_task_uuid_primary');
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
