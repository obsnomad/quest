<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;

/**
 * Class Schedule
 *
 * @property int $id
 * @property int $questId
 * @property Quest $quest
 * @property int $weekDay
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
     * @param null|array|int $questId
     * @param bool $withBookings
     * @param int $days
     * @return object
     */
    public static function getNextDays($questId = null, $withBookings = true, $days = 14)
    {
        $days = max(1, (int)$days);
        $exceptions = ScheduleException::query()
            ->where('date', '>=', \DB::raw('date(now())'))
            ->where('date', '<', \DB::raw("date(now() + interval $days day)"));
        if (is_array($questId)) {
            $exceptions->whereIn('quest_id', $questId);
        } elseif ($questId) {
            $exceptions->where('quest_id', $questId);
        }
        $exceptions = $exceptions->get()
            ->groupBy('quest_id')
            ->map(function ($value) {
                /**
                 * @var Collection $value
                 */
                return $value
                    ->keyBy('date')
                    ->map(function ($value) {
                        /**
                         * @var ScheduleException $value
                         */
                        return $value->treatAs;
                    });
            });

        $bookings = collect();
        if ($withBookings) {
            $bookings = Booking::active()
                ->where('date', '>=', \DB::raw('date(now())'))
                ->where('date', '<', \DB::raw("date(now() + interval $days day)"));
            if (is_array($questId)) {
                $bookings->whereIn('quest_id', $questId);
            } elseif ($questId) {
                $bookings->where('quest_id', $questId);
            }
            $bookings = $bookings->get()
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
                            return $value->date->toDateTimeString();
                        });
                });
        }
        $query = self::query();
        if (is_array($questId)) {
            $query->whereIn('quest_id', $questId);
        } elseif ($questId) {
            $query->where('quest_id', $questId);
        }
        $startTime = strtotime(date('Y-m-d 00:00:00'));
        $endTime = $startTime + $days * 86400;
        $prices = [];
        $result = $query
            ->get()
            ->groupBy('quest_id')
            ->map(function ($value, $id) use ($days, $exceptions, $bookings, $startTime, $endTime, &$prices) {
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
                $pricesQuest = [];
                for ($time = $startTime; $time < $endTime; $time += 86400) {
                    $dayFull = date('d.m.Y H:i:s', $time);
                    if ($exceptions->offsetExists($id) && $exceptions[$id]->offsetExists($dayFull)) {
                        $week_day = $exceptions[$id][$dayFull];
                    } else {
                        $week_day = (int)date('w', $time);
                    }
                    $week_day = $week_day ?: 7;
                    $day = date('Y-m-d', $time);
                    $data[$day] = $values[$week_day]->map(function ($value) use ($bookings, $id, $day, &$prices, &$pricesQuest) {
                        $date = $day . ' ' . $value->attributes['time'];
                        $dateParsed = Carbon::parse($date);
                        $prices[] = $value->price;
                        $pricesQuest[] = $value->price;
                        // Добавляется 4 часа, чтобы компенсировать часовой пояс
                        return (object)[
                            'date' => $date,
                            'realDay' => $dateParsed->formatLocalized('%e %b'),
                            'time' => $dateParsed->format('H:i'),
                            'price' => $value->price,
                            'booked' => $dateParsed < Carbon::now()->addHour(4) || $bookings->offsetExists($id) && in_array($date, $bookings[$id]->toArray()),
                        ];
                    });
                }
                $pricesQuest = array_unique($pricesQuest);
                sort($pricesQuest);
                $data->transform(function ($value, $day) use ($startTime, $pricesQuest) {
                    $day = Carbon::parse($day);
                    $startTime = Carbon::createFromTimestamp($startTime);
                    $weekDayRead = $day->formatLocalized('%a');
                    $diff = $day->diffInDays($startTime);
                    if (!$diff) {
                        $weekDayRead = 'Сегодня';
                    } elseif ($diff == 1) {
                        $weekDayRead = 'Завтра';
                    }
                    return (object)[
                        'day' => $day->toDateString(),
                        'realDay' => $day->formatLocalized('%e %b'),
                        'weekDay' => $weekDayRead,
                        'prices' => $pricesQuest,
                        'items' => $value,
                    ];
                });
                return $data;
            });
        if (!is_array($questId) && !is_null($questId)) {
            $result = $result->first();
        }
        $prices = array_unique($prices);
        sort($prices);
        return (object)[
            'items' => $result,
            'prices' => $prices,
        ];
    }

    public static function getByDate($date, $questId, $withBooking = true)
    {
        $date = Carbon::parse($date);
        $dateFormatted = Carbon::parse($date)->toDateTimeString();
        $exception = ScheduleException::query()
            ->where('schedule_exceptions.date', \DB::raw("date('$dateFormatted')"))
            ->where('schedule_exceptions.quest_id', $questId)
            ->leftJoin('schedule', function($join) {
                /**
                 * @var JoinClause $join
                 */
                $join
                    ->on('schedule_exceptions.quest_id', '=', 'schedule.quest_id')
                    ->on('schedule_exceptions.treat_as', '=', 'schedule.week_day');
            })
            ->first();
        $booking = false;
        if ($withBooking) {
            $booking = Booking::active()
                ->where('date', $dateFormatted)
                ->where('quest_id', $questId)
                ->first();
        }
        $scheduleItem = self::query()
            ->where('quest_id', $questId)
            ->where('week_day', (int)$date->format('N'))
            ->where('time', $date->format('H:i'))
            ->first();
        if($scheduleItem) {
            $scheduleItem->booked = !!$booking;
            $scheduleItem->price = $exception ? $exception->price : $scheduleItem->price;
            return $scheduleItem;
        }
        return null;
    }

    public function quest()
    {
        return $this->belongsTo(Quest::class, 'quest_id')->withDefault();
    }

    public function getWeekDayNameAttribute()
    {
        return Carbon::createFromDate(2007, 1, $this->weekDay)->formatLocalized('%A');
    }

    public function getTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
    }
}