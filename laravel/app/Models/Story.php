<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'description', 'cover_image'];

    //Relation avec les scènes de cette histoire
    public function scenes()
    {
        return $this->hasMany(Scene::class);
    }
    
    // Récupérer la première scène de l'histoire
    public function startingScene()
    {
        return $this->scenes()->where('id', 1)->first();
    }
    
    //Récupérer toutes les fins de cette histoire
    public function endings()
    {
        return $this->scenes()->where('is_ending', true)->get();
    }
    
    //Récupérer tous les objets qui peuvent être collectés dans cette histoire
    public function items()
    {
        return Item::whereHas('scenes', function($query) {
            $query->where('story_id', $this->id);
        })->get();
    }
}