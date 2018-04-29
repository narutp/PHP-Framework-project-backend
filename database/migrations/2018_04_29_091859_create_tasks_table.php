<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_name');
            $table->string('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->unsignedInteger('assignee_id');
            $table->foreign('assignee_id')->references('id')->on('users');
            $table->unsignedInteger('assignor_id');
            $table->foreign('assignor_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
