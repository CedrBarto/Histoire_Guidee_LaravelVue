<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\Scene;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Affiche la liste des histoires disponibles
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Pour l'API, renvoyer les données au format JSON
        if (request()->expectsJson()) {
            $stories = Story::all();
            return response()->json(['stories' => $stories]);
        }
        
        // Récupération de toutes les histoires disponibles
        $stories = Story::all();
        
        return view('stories.index', compact('stories'));
    }
    
    /**
     * Affiche le détail d'une histoire avant de commencer
     *
     * @param int $id Identifiant de l'histoire
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Pour l'API, renvoyer les données au format JSON
        if (request()->expectsJson()) {
            $story = Story::with('scenes')->findOrFail($id);
            return response()->json(['story' => $story]);
        }
        
        // Récupération de l'histoire avec son nombre de scènes
        $story = Story::findOrFail($id);
        $sceneCount = Scene::where('story_id', $id)->count();
        
        return view('stories.show', compact('story', 'sceneCount'));
    }
    
    /**
     * Récupère la première histoire pour l'API
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFirstStory()
    {
        $story = Story::first();
        return response()->json(['story' => $story]);
    }
    
    /**
     * Démarre une nouvelle histoire et redirige vers sa première scène
     *
     * @param int $id Identifiant de l'histoire à démarrer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start($id)
    {
        // Récupération de l'histoire
        $story = Story::findOrFail($id);
        
        // Réinitialisation de l'inventaire pour une nouvelle partie
        session(['inventory' => []]);
        
        // Récupération de la première scène
        $firstScene = Scene::where('story_id', $id)
                          ->where('id', 1)
                          ->firstOrFail();
        
        // Redirection vers la première scène
        return redirect()->route('scene.show', $firstScene->id);
    }

    /**
     * Store a newly created story
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // Add other validation rules as needed
        ]);
        
        $story = new Story();
        $story->title = $validated['title'];
        $story->description = $validated['description'];
        $story->save();
        
        return response()->json(['message' => 'Story created successfully', 'story' => $story], 201);
    }

    /**
     * Update the specified story
     */
    public function update(Request $request, $id)
    {
        $story = Story::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            // Add other validation rules as needed
        ]);
        
        $story->update($validated);
        
        return response()->json(['message' => 'Story updated successfully', 'story' => $story]);
    }

    /**
     * Remove the specified story
     */
    public function destroy($id)
    {
        $story = Story::findOrFail($id);
        $story->delete();
        
        return response()->json(['message' => 'Story deleted successfully']);
    }
}