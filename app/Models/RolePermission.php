<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;

/**
 * Class RolePermission
 * 
 * @property int $role_id
 * @property int $permission_id
 * @method static Builder|RolePermission whereRoleId($value)
 * @method static Builder|RolePermission wherePermissionId($value)
 */
class RolePermission extends Eloquent
{
	public $incrementing = false;
	protected $perPage = 20;
	public $timestamps = false;
	protected $primaryKey = ['role_id', 'permission_id'];

	protected $casts = [
		'role_id' => 'int',
		'permission_id' => 'int'
	];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id')->withDefault();
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id')->withDefault();
    }
}
