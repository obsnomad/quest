<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class Booking
 *
 * @property int $id
 * @property int $questId
 * @property Quest $quest
 * @property int $clientId
 * @property Client $client
 * @property int $statusId
 * @property Status $status
 * @property \Carbon\Carbon $date
 * @property string $dateFormatted
 * @property int $price
 * @property int $amount
 * @property string $comment
 * @property BookingHistory[]|Collection $history
 * @property int $updatedBy
 * @property User $user
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property boolean $notified
 * @method static Builder|Booking whereId($value)
 * @method static Builder|Booking whereQuestId($value)
 * @method static Builder|Booking whereClientId($value)
 * @method static Builder|Booking whereStatusId($value)
 * @method static Builder|Booking whereDate($value)
 * @method static Builder|Booking wherePrice($value)
 * @method static Builder|Booking whereCreatedAt($value)
 * @method static Builder|Booking whereUpdatedAt($value)
 * @method static Builder|Booking whereAmount($value)
 * @method static Builder|Booking whereComment($value)
 * @method static Builder|Booking whereUpdatedBy($value)
 * @method static Builder|Quest active()
 * @method static Builder|Quest new()
 * @method static Builder|Quest today()
 * @mixin \Eloquent
 */
class Booking extends Eloquent
{
    public $timestamps = true;
    protected $perPage = 20;
    protected $casts = [
        'quest_id' => 'int',
        'client_id' => 'int',
        'status_id' => 'int',
        'price' => 'int',
        'notified' => 'boolean',
    ];

    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'quest_id',
        'client_id',
        'status_id',
        'date',
        'price',
        'amount',
        'comment',
        'updated_by',
    ];

    protected $dispatchesEvents = [
        ''
    ];

    public function quest()
    {
        return $this->belongsTo(Quest::class, 'quest_id')->withDefault();
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id')->withDefault();
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id')->withDefault();
    }

    public function history()
    {
        return $this->hasMany(BookingHistory::class, 'booking_id')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }

    public function getDateFormattedAttribute()
    {
        return $this->date ? $this->date->format('d.m.Y H:i') : '';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status_id', [1, 2, 3]);
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeNew($query)
    {
        return $query->where('notified', '<>', 1);
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeToday($query)
    {
        return $query->where(\DB::raw('DATE(date)'), \DB::raw('DATE(NOW())'));
    }
}
