<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\Controller;
use App\Models\Scene;
use App\Models\Choice;
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
        
        // Si on change de scène, on réinitialise le compteur d'erreurs d'énigme
        session(['riddle_attempts' => 0]);
        
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
            'inventory' => $inventoryItems,
            'riddleError' => session('riddleError'),
            'riddleAttempts' => session('riddle_attempts', 0)
        ]);
    }
    
    /**
     * Traite le choix fait par l'utilisateur et redirige vers la prochaine scène
     */
    public function choose($choiceId)
{
    $choice = Choice::findOrFail($choiceId);
    $sessionToken = session('session_token', \Str::uuid());
    
    // Récupérer ou créer la session de jeu
    $gameSession = GameSession::firstOrCreate(
        ['session_token' => $sessionToken],
        ['current_scene_id' => 1]
    );
    
    // Mettre à jour la scène actuelle
    $gameSession->current_scene_id = $choice->next_scene_id;
    $gameSession->save();
    
    // Traiter les récompenses d'items
    if ($choice->item_reward) {
        $itemsAdded = [];
        
        if (is_array($choice->item_reward)) {
            // Cas où item_reward est un tableau d'IDs
            foreach ($choice->item_reward as $itemId) {
                $gameSession->items()->syncWithoutDetaching([$itemId]);
                $itemsAdded[] = Item::find($itemId);
            }
        } else {
            // Cas où item_reward est un seul ID
            $gameSession->items()->syncWithoutDetaching([$choice->item_reward]);
            $itemsAdded[] = Item::find($choice->item_reward);
        }
        
        // Afficher un message pour chaque item obtenu
        foreach ($itemsAdded as $item) {
            session()->flash('item_acquired', 
                session('item_acquired', '') . "Vous avez obtenu : {$item->name}. ");
        }
    }
    
    // Redirection vers la scène suivante
    return redirect()->route('scene.show', $choice->next_scene_id);
}
    
    /**
     * Traite la réponse à une énigme
     */
    public function solveRiddle(Request $request, $choiceId)
    {
        $choice = Choice::findOrFail($choiceId);
        $answer = $request->input('answer');
        
        // Récupère le nombre d'essais actuel
        $attempts = session('riddle_attempts', 0);
        
        // Vérifie si la réponse est correcte
        if ($choice->checkAnswer($answer)) {
            // Réponse correcte, on réinitialise le compteur d'essais
            session(['riddle_attempts' => 0]);
            
            // Redirige vers la prochaine scène
            return redirect()->route('scene.show', $choice->next_scene_id);
        } else {
            // Réponse incorrecte, on incrémente le compteur
            $attempts++;
            session(['riddle_attempts' => $attempts]);
            
            // Si 3+ tentatives échouées, mort
            if ($attempts >= 3) {
                session(['riddle_attempts' => 0]); // Réinitialise pour la prochaine fois
                session()->flash('message', 'Trop d\'erreurs! La punition est fatale...');
                return redirect()->route('scene.show', 96); // Redirection vers la mort (ID 96)
            }
            
            // Sinon, on reste sur la même scène avec message d'erreur
            $remainingAttempts = 3 - $attempts;
            session()->flash('riddleError', "Mauvaise réponse! Il vous reste {$remainingAttempts} tentative(s).");
            return redirect()->back();
        }
    }
    
    /**
     * Réinitialise la progression de l'histoire (pour recommencer)
     */
    public function restart($storyId)
    {
        session(['inventory' => []]);
        session(['riddle_attempts' => 0]);
        
        $firstScene = Scene::where('story_id', $storyId)
                          ->where('id', 1)
                          ->firstOrFail();
        
        return redirect()->route('scene.show', $firstScene->id);
    }
}