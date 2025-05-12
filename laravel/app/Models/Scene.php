<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scene extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
        'title', 
        'description', 
        'image', 
        'sound', 
        'is_ending',
        'story_id'
    ];

    protected $casts = [
        'is_ending' => 'boolean',
    ];

    // Relation avec l'histoire
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    // Relation avec les choix
    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}