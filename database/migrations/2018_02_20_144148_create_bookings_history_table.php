<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_history', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('booking_id')->unsigned();
            $table->tinyInteger('status_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->dateTime('date');
            $table->integer('price')->unsigned();
            $table->tinyInteger('amount')->unsigned();
            $table->text('comment');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_history');
    }
}
