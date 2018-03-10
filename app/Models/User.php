<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * Class User
 *
 * @property int $id
 * @property int $roleId
 * @property Role $role
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $rememberToken
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereRoleId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Eloquent implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    public $timestamps = false;
    protected $perPage = 20;
    protected $casts = [
        'role_id' => 'int'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'remember_token'
    ];

    protected $with = [
        'role.links.permission',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id')->withDefault();
    }

    /**
     * @return array
     */
    public function permissions()
    {
        return $this->role->links->map(function ($value) {
            /**
             * @var RolePermission $value
             */
            return $value->permission->name;
        })->toArray();
    }

    /**
     * Check if user has appropriate permissions
     * @param mixed $codes
     * @param string $type Should be 'and' or 'or'. Default is 'and'.
     * @return bool
     */
    public function hasPermissions($codes, $type = 'and')
    {
        if ($this->roleId == 1) {
            return true;
        }
        $count = count(array_intersect((array)$codes, $this->permissions()));
        return $type == 'or' ? $count > 0 : $count == count($codes);
    }
}
