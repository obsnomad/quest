<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class Quest
 *
 * @property int $id
 * @property int $questLocationId
 * @property string $name
 * @property string $description
 * @property string $descriptionHtml
 * @property int $status
 * @property string $picture
 * @property string|null $picturePath
 * @property int $level
 * @property string|null $levelReadable
 * @property int $time
 * @property string|null $timeReadable
 * @property float $players
 * @property string|null $playersReadable
 * @property int $price
 * @property string|null $priceReadable
 * @property string $special
 * @property string $specialStyle
 * @property string $slug
 * @property bool $active
 * @property bool $working
 * @property Booking[]|Collection $bookings
 * @property QuestLocation $location
 * @method static Builder|Quest whereId($value)
 * @method static Builder|Quest whereQuestLocationId($value)
 * @method static Builder|Quest whereName($value)
 * @method static Builder|Quest whereDescription($value)
 * @method static Builder|Quest whereStatus($value)
 * @method static Builder|Quest wherePicture($value)
 * @method static Builder|Quest whereLevel($value)
 * @method static Builder|Quest whereTime($value)
 * @method static Builder|Quest wherePlayers($value)
 * @method static Builder|Quest wherePrice($value)
 * @method static Builder|Quest whereSpecial($value)
 * @method static Builder|Quest whereSpecialStyle($value)
 * @method static Builder|Quest active()
 * @method static Builder|Quest working()
 * @mixin \Eloquent
 */
class Quest extends Eloquent
{
    public $timestamps = false;
    protected $perPage = 20;
    protected $fillable = [
        'quest_location_id',
        'name',
        'description',
        'status',
        'picture',
        'level',
        'time',
        'players',
        'price',
        'special',
        'special_style',
        'slug',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'quest_id')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }

    public function location()
    {
        return $this->belongsTo(QuestLocation::class, 'quest_location_id')->withDefault();
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', '>', 0);
    }

    public function getActiveAttribute()
    {
        return $this->status > 0;
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeWorking($query)
    {
        return $query->where('status', 1);
    }

    public function getWorkingAttribute()
    {
        return $this->status == 1;
    }

    public function getDescriptionHtmlAttribute()
    {
        return nl2br($this->description);
    }

    public function getLevelReadableAttribute()
    {
        switch ($this->status) {
            case 1:
                return 'Простой';
                break;
            case 2:
                return 'Несложный';
                break;
            case 3:
                return 'Сложный';
                break;
        }
        return null;
    }

    public function getPicturePathAttribute()
    {
        return $this->picture ? "/images/{$this->picture}" : null;
    }

    public function getTimeReadableAttribute()
    {
        return $this->time ? "{$this->time} минут" : null;
    }

    public function getPlayersReadableAttribute()
    {
        if (!$this->players) {
            return null;
        }
        $players = explode('.', $this->players);
        $players[1] = max((int)@$players[1], $players[0]);
        return ($players[1] > $players[0] ? "{$players[0]}-{$players[1]}" : $players[0]) . ' '
            . \Lang::choice('игрок|игрока|игроков', $players[1], [], 'ru');
    }

    public function getPriceReadableAttribute()
    {
        return $this->price ? "от {$this->price} рублей" : null;
    }
}
