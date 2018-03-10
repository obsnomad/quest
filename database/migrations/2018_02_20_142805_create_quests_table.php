<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quests', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('quest_location_id', 1);
            $table->string('name');
            $table->text('description');
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->string('picture', 30);
            $table->string('thumb', 30);
            $table->tinyInteger('level')->unsigned()->default(0);
            $table->smallInteger('time')->unsigned()->default(60);
            $table->float('players', 2, 1)->default(2.4);
            $table->integer('price')->unsigned();
            $table->string('special')->nullable();
            $table->string('special_style')->nullable();
            $table->string('slug', 10);
        });
        DB::table('quests')->insert([
            [
                'id' => 1,
                'quest_location_id' => 1,
                'name' => 'Ночь в музее',
                'description' => 'Ваша команда профессиональных воров затаилась под видом экспонатов среди экспозиций музея WONDER и дожидается наступления ночи. До очередного обхода охранника у вас есть ровно 60 минут, чтобы украсть главную ценность музея – пластину фараона Акменра, много лет оживляющую экспонаты музея естественной истории. Берегитесь! Легко не будет. Наш музей оснащен современной системой сигнализации. Для того, чтобы выбраться, придется проявить сообразительность и разгадать все загадки музея!',
                'status' => 1,
                'picture' => 'pic-museum.jpg',
                'thumb' => 'pic-museum-thumb.jpg',
                'level' => 2,
                'time' => 60,
                'players' => 2.5,
                'price' => 1600,
                'special' => null,
                'special_style' => null,
                'slug' => 'museum',
            ],
            [
                'id' => 2,
                'quest_location_id' => 2,
                'name' => 'Психбольница',
                'description' => 'Ходит легенда, что в 60-е годы на окраине Белгорода действовала незарегистрированная психиатрическая лечебница, в которой проводились нелегальные исследования человеческого разума. По этой легенде абсолютно здоровых людей помещали в палаты и наблюдали за их деградацией. Вам предстоит узнать, что происходит в этой больнице в наши дни, испытать на себе ужас пребывания в дурдоме, используя только логику и смекалку, попытаться найти выход и выяснить, куда один за другим пропадали пациенты психбольницы.',
                'status' => 2,
                'picture' => 'pic-psycho.jpg',
                'thumb' => 'pic-psycho-thumb.jpg',
                'level' => 2,
                'time' => 60,
                'players' => 2.5,
                'price' => 1600,
                'special' => null,
                'special_style' => null,
                'slug' => 'psycho',
            ],
            [
                'id' => 3,
                'quest_location_id' => 2,
                'name' => 'Секретные материалы',
                'description' => 'По данным архива Федеральной службы безопасности, в конце прошлого века в условиях строжайшей секретности на окраине нашей области действовала так называемая “Лаборатория Тьмы”. Её целью было исследование паранормальных явлений и страхов, а также возможных потусторонних сущностей. Однако проект вскоре был свернут.
После долгих поисков вам удалось установить её местоположение. Отважитесь ли вы войти в неё и узнать всю правду о том, что окружает вас в повседневной жизни?',
                'status' => 2,
                'picture' => 'pic-xfiles.jpg',
                'thumb' => 'pic-xfiles-thumb.jpg',
                'level' => 2,
                'time' => 60,
                'players' => 2.5,
                'price' => 1600,
                'special' => 'Страшный',
                'special_style' => 'dark',
                'slug' => 'xfiles',
            ],
            [
                'id' => 4,
                'quest_location_id' => 2,
                'name' => 'Фантом',
                'description' => 'Кажется, кто-то стоит за вашей спиной. Кто-то очень черный. Безумно черный. Вы чувствуете проникающие внутрь взгляд, но ничего не можете сделать. Обернуться не хватает сил, а смотреть вперед уже просто невозможно.
Здесь вы будете опираться только на свои ощущения. Финал квеста полностью зависит от вас. Приходи испытать свою команду.',
                'status' => 2,
                'picture' => 'pic-phantom.jpg',
                'thumb' => 'pic-phantom-thumb.jpg',
                'level' => 3,
                'time' => 90,
                'players' => 2.4,
                'price' => 2000,
                'special' => 'С закрытыми глазами',
                'special_style' => 'red',
                'slug' => 'phantom',
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
        Schema::dropIfExists('quests');
    }
}
