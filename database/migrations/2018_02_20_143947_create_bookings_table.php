<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('quest_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->smallInteger('status_id')->unsigned();
            $table->dateTime('date')->nullable();
            $table->integer('price')->unsigned()->nullable();
            $table->tinyInteger('amount')->unsigned()->nullable();
            $table->text('comment')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
