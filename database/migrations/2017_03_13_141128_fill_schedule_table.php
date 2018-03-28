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
            for($j = 10; $j < 25; $j+=1.25) {
                $price = 1700;
                $records[] = [
                    'quest_id' => 1,
                    'week_day' => $i,
                    'time' => \Carbon\Carbon::createFromTime(floor($j), ($j  - floor($j)) * 60)->toTimeString(),
                    'price' => $price,
                ];
                $records[] = [
                    'quest_id' => 2,
                    'week_day' => $i,
                    'time' => \Carbon\Carbon::createFromTime(floor($j), ($j  - floor($j)) * 60)->toTimeString(),
                    'price' => $price,
                ];
                $records[] = [
                    'quest_id' => 3,
                    'week_day' => $i,
                    'time' => \Carbon\Carbon::createFromTime(floor($j), ($j  - floor($j)) * 60 + 30)->toTimeString(),
                    'price' => $price,
                ];
            }
        }
        for($i = 6; $i <= 7; $i++) {
            for($j = 10; $j < 25; $j+=1.25) {
                $price = 2000;
                $records[] = [
                    'quest_id' => 1,
                    'week_day' => $i,
                    'time' => \Carbon\Carbon::createFromTime(floor($j), ($j  - floor($j)) * 60)->toTimeString(),
                    'price' => $price,
                ];
                $records[] = [
                    'quest_id' => 2,
                    'week_day' => $i,
                    'time' => \Carbon\Carbon::createFromTime(floor($j), ($j  - floor($j)) * 60)->toTimeString(),
                    'price' => $price,
                ];
                $records[] = [
                    'quest_id' => 3,
                    'week_day' => $i,
                    'time' => \Carbon\Carbon::createFromTime(floor($j), ($j  - floor($j)) * 60 + 30)->toTimeString(),
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
