<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class Quest
 * 
 * @property int $id
 * @property string $name
 * @property bool $active
 * @property Booking[]|Collection $bookings
 * @method static Builder|Quest whereId($value)
 * @method static Builder|Quest whereName($value)
 * @method static Builder|Quest whereActive($value)
 */
class Quest extends Eloquent
{
	protected $perPage = 20;
	public $timestamps = false;

	protected $casts = [
		'active' => 'bool'
	];

	protected $fillable = [
		'name',
		'active'
	];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'quest_id')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }
}
