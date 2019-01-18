<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * App\Models\Item
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Item query()
 * @mixin \Eloquent
 */
class Item extends Model
{
    use HasJsonRelationships;

    protected $fillable = [
        'price', 'name'
    ];

    public function kills()
    {
        return $this->hasManyJson(MonsterKill::class, 'loot->item_id', 'id');
    }
}
