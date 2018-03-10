<?php

use Illuminate\Database\Migrations\Migration;

class FillScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $records = [];
        for($i = 1; $i <= 5; $i++) {
            for($j = 9; $j < 24; $j+=1.5) {
                $price = $j < 17 ? 1600 : 1800;
                $records[] = [
                    'quest_id' => 1,
                    'week_day' => $i,
                    'time' => \Carbon\Carbon::createFromTime(floor($j), ($j  - floor($j)) * 60)->toTimeString(),
                    'price' => $price,
                ];
            }
        }
        for($i = 6; $i <= 7; $i++) {
            for($j = 9; $j < 24; $j+=1.5) {
                $price = $j < 17 ? 1800 : 2000;
                $records[] = [
                    'quest_id' => 1,
                    'week_day' => $i,
                    'time' => \Carbon\Carbon::createFromTime(floor($j), ($j  - floor($j)) * 60)->toTimeString(),
                    'price' => $price,
                ];
            }
        }
        DB::table('schedule')->insert($records);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('schedule')->truncate();
    }
}
