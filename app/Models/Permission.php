<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class Permission
 * 
 * @property int $id
 * @property string $name
 * @property RolePermission[]|Collection $links
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission whereName($value)
 */
class Permission extends Eloquent
{
	protected $perPage = 20;
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

    public function links()
    {
        return $this->hasMany(RolePermission::class, 'permission_id');
    }
}
