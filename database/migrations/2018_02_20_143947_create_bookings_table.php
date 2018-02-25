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
            $table->dateTime('date');
            $table->integer('price')->unsigned();
            $table->tinyInteger('amount')->unsigned();
            $table->timestamps();
        });
        DB::table('bookings')->insert([
            'id' => 1,
            'quest_id' => 1,
            'client_id' => 1,
            'status_id' => 1,
            'date' => DB::raw('now()'),
            'price' => 1600,
            'amount' => 4,
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()'),
        ]);
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
