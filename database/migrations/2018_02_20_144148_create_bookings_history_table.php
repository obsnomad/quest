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
            $table->integer('booking_id')->unsigned()->nullable();
            $table->tinyInteger('status_id')->unsigned()->nullable();
            $table->integer('client_id')->unsigned()->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('price')->unsigned()->nullable();
            $table->tinyInteger('amount')->unsigned()->nullable();
            $table->text('comment')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->dateTime('created_at')->useCurrent();
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
