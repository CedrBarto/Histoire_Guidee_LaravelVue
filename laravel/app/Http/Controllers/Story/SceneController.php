<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\Controller;
use App\Models\Scene;
use App\Models\Item;
use Illuminate\Http\Request;

class SceneController extends Controller
{
    /**
     * Affiche une scène spécifique avec ses choix et éléments d'interface
     */
    public function show($id)
    {
        $scene = Scene::with('choices')->findOrFail($id);
        
        // Pour l'API, renvoyer les données au format JSON
        if (request()->expectsJson()) {
            return response()->json(['scene' => $scene]);
        }
        
        // Si la scène est une fin et qu'elle est mauvaise, on réinitialise l'inventaire
        if ($scene->is_ending && $scene->id != 17) { // 17 est la bonne fin
            session(['inventory' => []]);
            session()->flash('message', 'Vous avez échoué! L\'aventure recommence...');
        }
        
        // Récupération de l'inventaire actuel depuis la session
        $inventory = session('inventory', []);
        
        // Récupération des objets complets pour l'affichage
        $inventoryItems = Item::whereIn('id', $inventory)->get();
        
        return view('story.scene', [
            'scene' => $scene, 
            'inventory' => $inventoryItems
        ]);
    }
    
    /**
     * Récupère la première scène pour l'API
     */
    public function getFirstScene()
    {
        $firstScene = Scene::where('is_first', true)->first();
        
        return response()->json([
            'scene' => $firstScene,
            'choices' => $firstScene->choices,
            'message' => 'Mode histoire sans sauvegarde'
        ]);
    }
    
    /**
     * Réinitialise la progression de l'histoire (pour recommencer)
     */
    public function restart($storyId)
    {
        session(['inventory' => []]);
        
        $firstScene = Scene::where('story_id', $storyId)
                          ->where('id', 1)
                          ->firstOrFail();
        
        return redirect()->route('scene.show', $firstScene->id);
    }

    /**
     * Display a listing of the scenes
     */
    public function index()
    {
        $scenes = Scene::all();
        
        return response()->json(['scenes' => $scenes]);
    }

    /**
     * Store a newly created scene
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'story_id' => 'required|exists:stories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_first' => 'boolean',
            'is_ending' => 'boolean',
            // Add other validation rules as needed
        ]);
        
        $scene = new Scene();
        $scene->story_id = $validated['story_id'];
        $scene->title = $validated['title'];
        $scene->content = $validated['content'];
        $scene->is_first = $validated['is_first'] ?? false;
        $scene->is_ending = $validated['is_ending'] ?? false;
        $scene->save();
        
        return response()->json(['message' => 'Scene created successfully', 'scene' => $scene], 201);
    }

    /**
     * Update the specified scene
     */
    public function update(Request $request, $id)
    {
        $scene = Scene::findOrFail($id);
        
        $validated = $request->validate([
            'story_id' => 'exists:stories,id',
            'title' => 'string|max:255',
            'content' => 'string',
            'is_first' => 'boolean',
            'is_ending' => 'boolean',
            // Add other validation rules as needed
        ]);
        
        $scene->update($validated);
        
        return response()->json(['message' => 'Scene updated successfully', 'scene' => $scene]);
    }

    /**
     * Remove the specified scene
     */
    public function destroy($id)
    {
        $scene = Scene::findOrFail($id);
        $scene->delete();
        
        return response()->json(['message' => 'Scene deleted successfully']);
    }
}