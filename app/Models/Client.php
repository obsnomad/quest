<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;

/**
 * Class Client
 * 
 * @property int $id
 * @property string $first_name
 * @property string $firstName
 * @property string $last_name
 * @property string $lastName
 * @property string $email
 * @property int $phone
 * @property int $vk_account_id
 * @property int $vkAccountId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $updatedAt
 * @method static Builder|Client whereId($value)
 * @method static Builder|Client whereFirstName($value)
 * @method static Builder|Client whereLastName($value)
 * @method static Builder|Client whereEmail($value)
 * @method static Builder|Client wherePhone($value)
 * @method static Builder|Client whereVkAccountId($value)
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereUpdatedAt($value)
 */
class Client extends Eloquent
{
	protected $perPage = 20;
	public $timestamps = true;

	protected $casts = [
		'phone' => 'int',
		'vk_account_id' => 'int'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'phone',
		'vk_account_id'
	];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'client_id')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }
}
