<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class MonsterKill extends Model
{
    use HasJsonRelationships;

    protected $fillable = [
        'user_id', 'monster_id', 'loot'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function monster()
    {
        return $this->belongsTo(Monster::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'loot->item_id');
    }
}
