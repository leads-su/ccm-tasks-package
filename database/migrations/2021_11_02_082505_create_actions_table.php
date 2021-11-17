<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateActionsTable
 */
class CreateActionsTable extends Migration
{
    /**
     * Table name
     * @var string
     */
    private string $tableName = 'actions';

    /**
     * Run the migrations.
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table): void {
            $table->id();
            $table->string('uuid');
            $table->string('name');
            $table->string('description');
            /**
             * @see \ConsulConfigManager\Tasks\Enums\ActionType
             */
            $table->integer('type')->default(0);
            $table->string('command');
            $table->json('arguments');
            $table->string('working_dir')->nullable();
            $table->string('run_as')->nullable();
            $table->boolean('use_sudo')->default(false);
            $table->boolean('fail_on_error')->default(true);
            $table->softDeletes();
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
