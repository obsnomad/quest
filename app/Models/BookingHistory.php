<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;

/**
 * Class BookingHistory
 * 
 * @property int $id
 * @property int $bookingId
 * @property Booking $booking
 * @property int $statusId
 * @property Status $status
 * @property int $clientId
 * @property Client $client
 * @property \Carbon\Carbon $date
 * @property int $price
 * @property int $amount
 * @property string $comment
 * @property \Carbon\Carbon $createdAt
 * @method static Builder|BookingHistory whereId($value)
 * @method static Builder|BookingHistory whereBookingId($value)
 * @method static Builder|BookingHistory whereStatusId($value)
 * @method static Builder|BookingHistory whereClientId($value)
 * @method static Builder|BookingHistory whereDate($value)
 * @method static Builder|BookingHistory wherePrice($value)
 * @method static Builder|BookingHistory whereComment($value)
 * @method static Builder|BookingHistory whereCreatedAt($value)
 */
class BookingHistory extends Eloquent
{
	protected $table = 'booking_history';
	protected $perPage = 20;
	public $timestamps = true;

	const UPDATED_AT = null;

	protected $casts = [
		'booking_id' => 'int',
		'status_id' => 'int',
		'client_id' => 'int',
		'price' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'booking_id',
		'status_id',
		'client_id',
		'date',
		'price',
		'comment'
	];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id')->withDefault();
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id')->withDefault();
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id')->withDefault();
    }
}
