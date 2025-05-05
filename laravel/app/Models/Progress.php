<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_id',
        'current_scene_id',
        'completed',
        'collected_items'
    ];

    //Stocke les items collectés dans un champ JSON appelé collected_items, 
    //qui est un tableau d'IDs d'items.
    protected $casts = [
        'completed' => 'boolean',
        'collected_items' => 'json',
    ];

    // User relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Story relationship
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    // Current scene relationship
    public function currentScene()
    {
        return $this->belongsTo(Scene::class, 'current_scene_id');
    }

    // Check if user has a specific item
    public function hasItem($itemId)
    {
        $items = $this->collected_items ?? [];
        return in_array($itemId, $items);
    }

    // Add an item to collection
    public function addItem($itemId)
    {
        $items = $this->collected_items ?? [];
        if (!in_array($itemId, $items)) {
            $items[] = $itemId;
            $this->collected_items = $items;
            $this->save();
        }
        return $this;
    }

    // Add multiple items at once
    public function addItems(array $itemIds)
    {
        $items = $this->collected_items ?? [];
        $changed = false;
        
        foreach ($itemIds as $itemId) {
            if (!in_array($itemId, $items)) {
                $items[] = $itemId;
                $changed = true;
            }
        }
        
        if ($changed) {
            $this->collected_items = $items;
            $this->save();
        }
        
        return $this;
    }
}