<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Schedule
 *
 * @property int $id
 * @property int $quest_id
 * @property int $week_day
 * @property string $week_day_name
 * @property Carbon $time
 * @property int $price
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereQuestId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Schedule whereWeekDay($value)
 * @mixin \Eloquent
 */
class Schedule extends Model
{
    protected $table = 'schedule';

    public $timestamps = false;

    protected $fillable = [
        'quest_id',
        'week_day',
        'time',
        'price'
    ];

    protected $guarded = [];

    public function getWeekDayNameAttribute()
    {
        return Carbon::createFromDate(2007, 1, $this->week_day)->format('l');
    }

    public function getTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
    }
}