<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class Client
 *
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $fullName
 * @property string $email
 * @property int $phone
 * @property string $phoneFormatted
 * @property int $vkAccountId
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property Booking[]|Collection $bookings
 * @method static Builder|Client whereId($value)
 * @method static Builder|Client whereFirstName($value)
 * @method static Builder|Client whereLastName($value)
 * @method static Builder|Client whereEmail($value)
 * @method static Builder|Client wherePhone($value)
 * @method static Builder|Client whereVkAccountId($value)
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Client extends Eloquent
{
    protected $perPage = 20;
    public $timestamps = true;

    protected $casts = [
        'vk_account_id' => 'int',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'vk_account_id',
    ];

    protected $appends = [
        'full_name',
        'phone_formatted',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'client_id')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }

    public function getFullNameAttribute()
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    public function getPhoneFormattedAttribute()
    {
        return $this->phone ? '+7 (' . substr($this->phone, 0, 3) . ') ' . substr($this->phone, 3, 3) . '-'
            . substr($this->phone, 6, 2) . '-' . substr($this->phone, 8, 2) : '';
    }

    public static function cleanPhone($value) {
        return preg_replace(['/[^0-9]/', '/^7/'], '', $value);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = self::cleanPhone($value);
    }
}
