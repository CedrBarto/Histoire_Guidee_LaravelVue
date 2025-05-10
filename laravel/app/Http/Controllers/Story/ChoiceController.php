<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Scene;
use Illuminate\Http\Request;

class ChoiceController extends Controller
{
    /**
     * Process a player choice and return the next scene
     */
    public function makeChoice($id)
    {
        $choice = Choice::findOrFail($id);
        $nextScene = Scene::with('choices')->findOrFail($choice->next_scene_id);
        
        return response()->json(['scene' => $nextScene]);
    }
    
    /**
     * Get all available choices for a specific scene
     */
    public function getChoicesForScene($sceneId)
    {
        $choices = Choice::where('scene_id', $sceneId)->get();
        
        return response()->json(['choices' => $choices]);
    }

    /**
     * Display a listing of all choices
     */
    public function index()
    {
        $choices = Choice::all();
        
        return response()->json(['choices' => $choices]);
    }

    /**
     * Display the specified choice
     */
    public function show($id)
    {
        $choice = Choice::findOrFail($id);
        
        return response()->json(['choice' => $choice]);
    }

    /**
     * Store a newly created choice
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scene_id' => 'required|exists:scenes,id',
            'text' => 'required|string|max:255',
            'next_scene_id' => 'required|exists:scenes,id',
            // Add other validation rules as needed
        ]);
        
        $choice = new Choice();
        $choice->scene_id = $validated['scene_id'];
        $choice->text = $validated['text'];
        $choice->next_scene_id = $validated['next_scene_id'];
        $choice->save();
        
        return response()->json(['message' => 'Choice created successfully', 'choice' => $choice], 201);
    }

    /**
     * Update the specified choice
     */
    public function update(Request $request, $id)
    {
        $choice = Choice::findOrFail($id);
        
        $validated = $request->validate([
            'scene_id' => 'exists:scenes,id',
            'text' => 'string|max:255',
            'next_scene_id' => 'exists:scenes,id',
            // Add other validation rules as needed
        ]);
        
        $choice->update($validated);
        
        return response()->json(['message' => 'Choice updated successfully', 'choice' => $choice]);
    }

    /**
     * Remove the specified choice
     */
    public function destroy($id)
    {
        $choice = Choice::findOrFail($id);
        $choice->delete();
        
        return response()->json(['message' => 'Choice deleted successfully']);
    }
}