<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSequencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->foreignId('from_task_id');
            $table->foreignId('to_task_id');
            $table->timestamps();

            $table->foreign('from_task_id')->references('id')->on('tasks')->onDelete('CASCADE');
            $table->foreign('to_task_id')->references('id')->on('tasks')->onDelete('CASCADE');

            $table->primary(['from_task_id', 'to_task_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sequences');
    }
}
