<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->smallIncrements('id')->unsigned();
            $table->string('name');
            $table->enum('type', ['pending', 'approved', 'success', 'failed'])->index('type_idx');
            $table->smallInteger('sort')->unsigned();
        });
        DB::table('statuses')->insert([
            [
                'id' => 1,
                'name' => 'В ожидании',
                'type' => 'pending',
                'sort' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Подтверждено',
                'type' => 'approved',
                'sort' => 2,
            ],
            [
                'id' => 3,
                'name' => 'Проведено',
                'type' => 'success',
                'sort' => 3,
            ],
            [
                'id' => 4,
                'name' => 'Отменено',
                'type' => 'failed',
                'sort' => 4,
            ],
            [
                'id' => 5,
                'name' => 'Не пришли',
                'type' => 'failed',
                'sort' => 5,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
