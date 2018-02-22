<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;

/**
 * Class ScheduleException
 * 
 * @property int $id
 * @property \Carbon\Carbon $date
 * @property int $treat_as
 * @property int $treatAs
 * @method static Builder|ScheduleException whereId($value)
 * @method static Builder|ScheduleException whereDate($value)
 * @method static Builder|ScheduleException whereTreatAs($value)
 */
class ScheduleException extends Eloquent
{
	protected $perPage = 20;
	public $timestamps = false;

	protected $casts = [
		'treat_as' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'date',
		'treat_as'
	];
}
