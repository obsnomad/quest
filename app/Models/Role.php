<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class Role
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property RolePermission[]|Collection $links
 * @property User[]|Collection $users
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereCode($value)
 * @method static Builder|Role whereName($value)
 * @mixin \Eloquent
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

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
