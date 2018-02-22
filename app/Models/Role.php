<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;

/**
 * Class Role
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereCode($value)
 * @method static Builder|Role whereName($value)
 */
class Role extends Eloquent
{
	protected $perPage = 20;
	public $timestamps = false;

	protected $fillable = [
		'code',
		'name'
	];

    public function links()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }
}
