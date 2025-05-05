<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description'
    ];

    //Relation avec les scènes où cet objet peut être obtenu
    public function scenes()
    {
        return $this->hasMany(Scene::class, 'item_reward');
    }
}