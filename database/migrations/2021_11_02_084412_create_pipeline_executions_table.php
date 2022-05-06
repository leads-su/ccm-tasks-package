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
            $table->uuid('uuid');
            $table->foreignUuid('pipeline_uuid');
            /**
             * @see \ConsulConfigManager\Tasks\Enums\ExecutionState
             */
            $table->integer('state')->default(0);
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
