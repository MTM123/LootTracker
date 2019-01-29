<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * App\Models\MonsterKill
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Item[] $items
 * @property-read \App\Models\Monster $monster
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MonsterKill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MonsterKill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MonsterKill query()
 * @mixin \Eloquent
 */
class MonsterKill extends Model
{
    use HasJsonRelationships;

    protected $fillable = [
        'user_id', 'monster_id', 'loot', 'created_at'
    ];

    protected $casts = ['loot' => 'json'];

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
        return $this->belongsToJson(Item::class, 'loot[]->item_id', 'item_id');
    }
}
