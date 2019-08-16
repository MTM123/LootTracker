<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drops extends Model
{
    protected $fillable = [
        'item_id', 'price', 'name', 'qty'
    ];
}

