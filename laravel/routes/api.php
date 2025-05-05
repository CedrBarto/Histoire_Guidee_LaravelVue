<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Story\ProgressController;
use App\Http\Controllers\Story\StoryController;
use App\Http\Controllers\Story\SceneController;

//Toutes les routes

// Route publique pour vérifier que l'API fonctionne
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    // Informations utilisateur
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Routes pour les histoires
    Route::prefix('stories')->group(function () {
        // Liste des histoires (API)
        Route::get('/', function () {
            $stories = \App\Models\Story::all();
            return response()->json(['stories' => $stories]);
        });
        
        // Détails d'une histoire (API)
        Route::get('/{id}', function ($id) {
            $story = \App\Models\Story::with('scenes')->findOrFail($id);
            return response()->json(['story' => $story]);
        });
    });
    
    // Routes pour la progression
    Route::prefix('progress')->group(function () {
        // Obtenir la progression actuelle
        Route::get('/{storyId}', [ProgressController::class, 'getProgress']);
        
        // Démarrer/reprendre une histoire
        Route::post('/story/{storyId}/start', [ProgressController::class, 'startStory']);
        
        // Faire un choix
        Route::post('/{progressId}/choice', [ProgressController::class, 'makeChoice']);
        
        // Recommencer après une mort
        Route::post('/{progressId}/restart', [ProgressController::class, 'restart']);
        
        // Réinitialiser la progression
        Route::post('/story/{storyId}/reset', [ProgressController::class, 'resetProgress']);
        
        // Obtenir l'inventaire
        Route::get('/inventory/{storyId}', [ProgressController::class, 'getInventory']);
    });
    
    // Route pour obtenir une scène spécifique
    Route::get('/scenes/{id}', function ($id) {
        $scene = \App\Models\Scene::with('choices')->findOrFail($id);
        return response()->json(['scene' => $scene]);
    });
});