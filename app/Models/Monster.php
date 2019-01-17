<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
