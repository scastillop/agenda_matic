<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('owner_id');
            $table->foreign('owner_id')->references('id')->on('users');
            $table->enum('type', array('meeting', 'off'));
            $table->enum('status', array('scheduled', 'canceled'));
            $table->boolean('rejectable');
            $table->timestamp('start');
            $table->timestamp('end');
            $table->text('details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
