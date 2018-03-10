<?php

namespace App\Models;

use App\Models\BaseModel as Eloquent;
use Illuminate\Database\Query\Builder;

/**
 * Class Status
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $sort
 * @property string $labelClass
 * @method static Builder|Status whereId($value)
 * @method static Builder|Status whereName($value)
 * @method static Builder|Status whereType($value)
 * @method static Builder|Status whereSort($value)
 * @mixin \Eloquent
 */
class Status extends Eloquent
{
    protected $perPage = 20;
    public $timestamps = false;

    protected $casts = [
        'sort' => 'int'
    ];

    protected $fillable = [
        'name',
        'type',
        'sort'
    ];

    public function getLabelClassAttribute()
    {
        switch ($this->attributes['type']) {
            case 'pending':
                return 'warning';
            case 'approved':
                return 'info';
            case 'success':
                return 'success';
            case 'failed':
                return 'danger';
            default:
                return 'default';
        }
    }
}
