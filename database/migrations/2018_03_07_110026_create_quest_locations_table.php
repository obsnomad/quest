<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->string('description');
            $table->timestamps();
            $table->float('lat', 8, 6);
            $table->float('lon', 8, 6);
        });
        DB::table('quest_locations')->insert([
            [
                'id' => 1,
                'name' => 'Квесты на 5 Августа',
                'address' => 'г. Белгород, ул. 5 Августа, 31',
                'description' => 'цокольный этаж, вход со двора в офис №4, между подъездами 1 и 2',
                'lat' => 50.583106,
                'lon' => 36.576454,
            ],
            [
                'id' => 2,
                'name' => 'Квесты на Губкина',
                'address' => 'г. Белгород, ул. Губкина, 17и',
                'description' => 'цокольный этаж, правая сторона здания',
                'lat' => 50.566160,
                'lon' => 36.579012,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quest_locations');
    }
}
