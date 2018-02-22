<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;

/**
 * Class Status
 * 
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $sort
 * @method static Builder|Status whereId($value)
 * @method static Builder|Status whereName($value)
 * @method static Builder|Status whereType($value)
 * @method static Builder|Status whereSort($value)
 */
class Status extends Eloquent
{
	protected $perPage = 20;
	public $timestamps = false;

	protected $casts = [
		'sort' => 'int'
	];

	protected $fillable = [
		'name',
		'type',
		'sort'
	];
}
