<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MonsterKill[] $kills
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * User boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->api_token = str_random(12);
        });
    }

    public function kills()
    {
        return $this->hasMany(MonsterKill::class);
    }

    public function getLastKills($amount){
        return $this->kills()
            ->orderBy('id', 'desc')
            ->limit($amount)
            ->get();
    }

    public function sortedKills()
    {
        return $this->kills()
            ->select('*', DB::raw('count(*) as count'))
            ->leftJoin('monsters', 'monster_kills.monster_id', '=', 'monsters.id')
            ->orderBy('monsters.name', 'asc')
            ->orderBy('monsters.level', 'asc')
            ->groupBy('monster_kills.monster_id')
            ->get();
    }
}
