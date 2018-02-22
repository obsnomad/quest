<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class Schedule
 *
 * @property int $id
 * @property int $quest_id
 * @property int $questId
 * @property int $week_day
 * @property int $weekDay
 * @property int $week_day_name
 * @property int $weekDayName
 * @property \Carbon\Carbon $time
 * @property int $price
 * @method static Builder|Schedule whereId($value)
 * @method static Builder|Schedule whereQuestId($value)
 * @method static Builder|Schedule whereWeekDay($value)
 * @method static Builder|Schedule whereTime($value)
 * @method static Builder|Schedule wherePrice($value)
 * @mixin \Eloquent
 */
class Schedule extends Eloquent
{
    public $timestamps = false;
    protected $table = 'schedule';
    protected $perPage = 20;
    protected $casts = [
        'quest_id' => 'int',
        'week_day' => 'int',
        'price' => 'int'
    ];

    protected $dates = [
        'time'
    ];

    protected $fillable = [
        'quest_id',
        'week_day',
        'time',
        'price'
    ];

    /**
     * Получить список на следующие {$days} дней
     *
     * @param int $questId
     * @param bool $withBookings
     * @param int $days
     * @return Collection
     */
    public static function getNextDays($questId, $withBookings = true, $days = 14)
    {
        $days = max(1, (int)$days);
        $bookings = collect();
        if ($withBookings) {
            $bookings = Booking::query()
                ->where('date', '>=', \DB::raw('date(now())'))
                ->where('date', '<', \DB::raw("date(now() + interval $days day)"))
                ->get()
                ->groupBy('quest_id')
                ->map(function ($value) {
                    /**
                     * @var Collection $value
                     */
                    return $value
                        ->map(function ($value) {
                            /**
                             * @var Booking $value
                             */
                            return $value->date;
                        });
                });
        }
        $query = self::query();
        if (is_array($questId)) {
            $query->whereIn('quest_id', $questId);
        } else {
            $query->where('quest_id', $questId);
        }
        $startTime = strtotime(date('Y-m-d 00:00:00'));
        $endTime = $startTime + $days * 86400;
        $result = $query
            ->get()
            ->groupBy('quest_id')
            ->map(function ($value, $id) use ($days, $bookings, $startTime, $endTime) {
                /**
                 * @var Collection $value
                 */
                $values = $value
                    ->groupBy('week_day')
                    ->map(function ($value) {
                        /**
                         * @var Collection $value
                         */
                        return $value->keyBy('time');
                    });
                $data = collect();
                for ($time = $startTime; $time < $endTime; $time += 86400) {
                    $week_day = (int)date('w', $time);
                    $week_day = $week_day ?: 7;
                    $day = date('Y-m-d', $time);
                    $data[$day] = $values[$week_day]->map(function ($value) use ($bookings, $id, $day) {
                        $date = $day . ' ' . $value->attributes['time'];
                        return (object)[
                            'price' => $value->price,
                            'booked' => $bookings->offsetExists($id) && in_array($date, $bookings[$id]),
                        ];
                    });
                }
                return $data;
            });
        if (!is_array($questId)) {
            return $result->first();
        }
        return $result;
    }

    public function getWeekDayNameAttribute()
    {
        return Carbon::createFromDate(2007, 1, $this->weekDay)->format('l');
    }

    public function getTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
    }
}