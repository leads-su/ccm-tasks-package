<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTaskActionsTable
 */
class CreateTaskActionsTable extends Migration
{
    /**
     * Table name
     * @var string
     */
    private string $tableName = 'task_actions';

    /**
     * Run the migrations.
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table): void {
            $table->unsignedBigInteger('task_uuid');
            $table->unsignedBigInteger('action_uuid');
            $table->integer('order');

            $table->foreign('task_uuid')
                ->references('uuid')
                ->on('tasks')
                ->onDelete('cascade');

            $table->foreign('action_uuid')
                ->references('uuid')
                ->on('actions')
                ->onDelete('cascade');

            $table->primary([
                'pipeline_uuid',
                'task_uuid',
            ], 'pipeline_task_actions_task_uuid_action_uuid_primary');
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
