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
            $table->unsignedBigInteger('pipeline_uuid');
            $table->unsignedBigInteger('task_uuid');
            $table->integer('order');

            $table->foreign('pipeline_uuid')
                ->references('uuid')
                ->on('pipelines')
                ->onDelete('cascade');

            $table->foreign('task_uuid')
                ->references('uuid')
                ->on('tasks')
                ->onDelete('cascade');

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
