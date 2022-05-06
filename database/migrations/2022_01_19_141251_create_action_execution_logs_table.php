<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateActionExecutionLogsTable
 */
class CreateActionExecutionLogsTable extends Migration
{
    /**
     * Table name
     * @var string
     */
    private string $tableName = 'action_execution_logs';

    /**
     * Run the migrations.
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table): void {
            $table->id();
            $table->foreignId('action_execution_id');
            $table->integer('exit_code');
            $table->json('output');
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
