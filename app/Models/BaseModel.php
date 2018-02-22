<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BaseModel
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class BaseModel extends Model
{
    public function getAttribute($key)
    {
        $key = snake_case($key);
        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        $key = snake_case($key);
        return parent::setAttribute($key, $value);
    }

    public function __isset($key)
    {
        return parent::__isset(snake_case($key));
    }

    public function __unset($key)
    {
        parent::__unset(snake_case($key));
    }

    /**
     * Получение списка элементов, подготовленных для вывода в виде select
     *
     * @param string $orderBy
     * @param bool $addEmpty
     * @return $this|\Illuminate\Support\Collection|static
     */
    public static function getSelectList($orderBy = null, $addEmpty = true) {
        $query = static::query();
        if($orderBy) {
            list($orderBy, $orderDir) = substr($orderBy, 0, 1) == '-' ? [substr($orderBy, 1, strlen($orderBy)), 'desc'] : [$orderBy, 'asc'];
            $query->orderBy($orderBy, $orderDir);
        }
        $result = $query
            ->get()
            ->keyBy('id')
            ->map(function($value) {
                return $value->name;
            });
        if($addEmpty) {
            $result = $result->prepend('', '');
        }
        return $result;
    }
}
