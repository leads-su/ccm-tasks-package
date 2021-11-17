<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePipelineExecutionsTable
 */
class CreatePipelineExecutionsTable extends Migration
{
    /**
     * Table name
     * @var string
     */
    private string $tableName = 'pipeline_executions';

    /**
     * Run the migrations.
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table): void {
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('pipeline_uuid');
            $table->unsignedBigInteger('task_uuid');
            /**
             * @see \ConsulConfigManager\Tasks\Enums\ExecutionState
             */
            $table->integer('state')->default(0);
            $table->timestamps();

            $table->foreign('pipeline_uuid')
                ->references('uuid')
                ->on('pipelines')
                ->onDelete('cascade');

            $table->foreign('task_uuid')
                ->references('uuid')
                ->on('tasks')
                ->onDelete('cascade');
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
