<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * Class QuestLocation
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $description
 * @property float $lat
 * @property float $lon
 * @property array $coords
 * @property Quest[]|Collection $quests
 * @method static Builder|QuestLocation whereId($value)
 * @method static Builder|QuestLocation whereAddress($value)
 * @method static Builder|QuestLocation whereDescription($value)
 * @mixin \Eloquent
 */
class QuestLocation extends Eloquent
{
    public $timestamps = false;
    protected $perPage = 20;

    protected $fillable = [
        'name',
        'address',
        'description',
        'lat',
        'lon',
    ];

    protected $appends = [
        'coords',
    ];

    public function quests()
    {
        return $this->hasMany(Quest::class, 'quest_location_id');
    }

    public function getCoordsAttribute() {
        return [$this->lat, $this->lon];
    }
}
