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

    // Relation with story
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    // Relation with choices
    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}