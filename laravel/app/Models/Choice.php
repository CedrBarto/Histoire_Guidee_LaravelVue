<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'next_scene_id',
        'scene_id',
        'item_reward',
        'additional_rewards',
        'type',
        'answer'
    ];

    // Cast item_reward en JSON pour gérer les tableaux d'items
    protected $casts = [
        'item_reward' => 'json',
        'additional_rewards' => 'json',
    ];

    // Relationship with the scene this choice belongs to
    public function scene()
    {
        return $this->belongsTo(Scene::class);
    }

    // Relationship with the next scene this choice leads to
    public function nextScene()
    {
        return $this->belongsTo(Scene::class, 'next_scene_id');
    }
    
    // Obtenir tous les items associés à ce choix
    public function getAllRewardItems()
    {
        $items = collect();
        
        // Ajouter l'item principal s'il existe
        if ($this->item_reward) {
            $items = $items->merge(Item::where('id', $this->item_reward)->get());
        }
        
        // Ajouter les items additionnels s'ils existent
        if ($this->additional_rewards) {
            $items = $items->merge(Item::whereIn('id', $this->additional_rewards)->get());
        }
        
        return $items;
    }
    
    // Obtenir tous les IDs des items de récompense
    public function getAllRewardItemIds()
    {
        $ids = [];
        
        if ($this->item_reward) {
            $ids[] = $this->item_reward;
        }
        
        if ($this->additional_rewards) {
            $ids = array_merge($ids, $this->additional_rewards);
        }
        
        return $ids;
    }

    // Vérifie si ce choix est une énigme
    public function isRiddle()
    {
        return $this->type === 'riddle';
    }

    // Vérifie si la réponse fournie est correcte
    public function checkAnswer($response)
    {
        // Code existant inchangé
        $normalizedResponse = trim(strtolower($response));
        
        $acceptableAnswers = explode(',', $this->answer);
        
        foreach ($acceptableAnswers as $acceptableAnswer) {
            if (trim(strtolower($acceptableAnswer)) === $normalizedResponse) {
                return true;
            }
        }
        
        return false;
    }
}