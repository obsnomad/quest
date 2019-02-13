<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingProps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('notified')->default(0);
        });
        \DB::table('bookings')->update(['notified' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('bookings', function(Blueprint $table) {
            $table->dropColumn('notified');
        });
    }
}
