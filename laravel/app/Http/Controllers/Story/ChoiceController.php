<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Scene;
use Illuminate\Http\Request;

class ChoiceController extends Controller
{
    /**
     * Gère les choix de l'utilisateur dans le jeu
     */
    public function makeChoice($id)
    {
        $choice = Choice::findOrFail($id);
        $nextScene = Scene::with('choices')->findOrFail($choice->next_scene_id);
        
        return response()->json(['scene' => $nextScene]);
    }
    
    /**
     * Récupère tous les choix disponibles pour une scène spécifique
     */
    public function getChoicesForScene($sceneId)
    {
        $choices = Choice::where('scene_id', $sceneId)->get();
        
        return response()->json(['choices' => $choices]);
    }

    /**
     * Affiche une liste de tous les choix
     */
    public function index()
    {
        $choices = Choice::all();
        
        return response()->json(['choices' => $choices]);
    }

    /**
     * Affiche un choix spécifique
     */
    public function show($id)
    {
        $choice = Choice::findOrFail($id);
        
        return response()->json(['choice' => $choice]);
    }

    /**
     *  Crée un nouveau choix
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scene_id' => 'required|exists:scenes,id',
            'text' => 'required|string|max:255',
            'next_scene_id' => 'required|exists:scenes,id',
        ]);
        
        $choice = new Choice();
        $choice->scene_id = $validated['scene_id'];
        $choice->text = $validated['text'];
        $choice->next_scene_id = $validated['next_scene_id'];
        $choice->save();
        
        return response()->json(['message' => 'Choice created successfully', 'choice' => $choice], 201);
    }

    /**
     * Met à jour le choix spécifié
     */
    public function update(Request $request, $id)
    {
        $choice = Choice::findOrFail($id);
        
        $validated = $request->validate([
            'scene_id' => 'exists:scenes,id',
            'text' => 'string|max:255',
            'next_scene_id' => 'exists:scenes,id',
        ]);
        
        $choice->update($validated);
        
        return response()->json(['message' => 'Choice updated successfully', 'choice' => $choice]);
    }

    /**
     * Supprime le choix spécifié
     */
    public function destroy($id)
    {
        $choice = Choice::findOrFail($id);
        $choice->delete();
        
        return response()->json(['message' => 'Choice deleted successfully']);
    }
}