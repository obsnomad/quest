<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;

/**
 * Class ScheduleException
 *
 * @property int $id
 * @property int $questId
 * @property Quest $quest
 * @property \Carbon\Carbon $date
 * @property int $treatAs
 * @method static Builder|ScheduleException whereId($value)
 * @method static Builder|ScheduleException whereQuestId($value)
 * @method static Builder|ScheduleException whereDate($value)
 * @method static Builder|ScheduleException whereTreatAs($value)
 * @mixin \Eloquent
 */
class ScheduleException extends Eloquent
{
    protected $perPage = 20;
    public $timestamps = false;

    protected $casts = [
        'treat_as' => 'int'
    ];

    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'date',
        'treat_as'
    ];

    public function quest()
    {
        return $this->belongsTo(Quest::class, 'quest_id')->withDefault();
    }
}
