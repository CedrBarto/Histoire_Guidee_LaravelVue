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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupération de toutes les histoires disponibles
        $stories = Story::all();
        
        return view('stories.index', compact('stories'));
    }
    
    /**
     * Affiche le détail d'une histoire avant de commencer
     *
     * @param int $id Identifiant de l'histoire
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Récupération de l'histoire avec son nombre de scènes
        $story = Story::findOrFail($id);
        $sceneCount = Scene::where('story_id', $id)->count();
        
        return view('stories.show', compact('story', 'sceneCount'));
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
}