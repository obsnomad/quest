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
 * @property BookingHistory[]|Collection $history
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @method static Builder|Booking whereId($value)
 * @method static Builder|Booking whereQuestId($value)
 * @method static Builder|Booking whereClientId($value)
 * @method static Builder|Booking whereStatusId($value)
 * @method static Builder|Booking whereDate($value)
 * @method static Builder|Booking wherePrice($value)
 * @method static Builder|Booking whereCreatedAt($value)
 * @method static Builder|Booking whereUpdatedAt($value)
 */
class Booking extends Eloquent
{
    public $timestamps = true;
    protected $perPage = 20;
    protected $casts = [
        'quest_id' => 'int',
        'client_id' => 'int',
        'status_id' => 'int',
        'price' => 'int'
    ];

    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'quest_id',
        'client_id',
        'status_id',
        'date',
        'price'
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

    public function getDateFormattedAttribute() {
        return $this->date->format('d.m.Y H:i');
    }
}
