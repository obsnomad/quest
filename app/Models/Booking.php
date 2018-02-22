<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;

/**
 * Class Booking
 *
 * @property int $id
 * @property int $quest_id
 * @property int $questId
 * @property int $client_id
 * @property int $clientId
 * @property int $status_id
 * @property int $statusId
 * @property \Carbon\Carbon $date
 * @property int $price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updated_at
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
        return $this->hasMany(BookingsHistory::class, 'booking_id')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }
}
