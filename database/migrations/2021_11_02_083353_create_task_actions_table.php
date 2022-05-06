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
            $table->string('uuid')->primary();
            $table->foreignUuid('task_uuid');
            $table->foreignUuid('action_uuid');
            $table->integer('order');
            $table->timestamps();
            $table->softDeletes();
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
