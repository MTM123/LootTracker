<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Monster
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MonsterKill[] $kills
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Monster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Monster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Monster query()
 * @mixin \Eloquent
 */
class Monster extends Model
{
    protected $fillable = [
        'name', 'level'
    ];

    public function kills()
    {
        return $this->hasMany(MonsterKill::class);
    }
}
