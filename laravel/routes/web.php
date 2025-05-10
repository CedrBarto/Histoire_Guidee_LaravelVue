<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Story\StoryController;
use App\Http\Controllers\Story\SceneController;
use App\Http\Controllers\Story\ChoiceController;
use App\Http\Controllers\Story\ItemController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ApiController;

// Route d'accueil - accessible sans authentication
Route::get('/', function () {
    return view('welcome');
});

// Défini une redirection vers la page login si l'utilisateur n'est pas authentifié
Route::middleware(['auth'])->group(function () {
    // Route home après connexion
    Route::get('/home', function () {
        return view('app');
    })->name('home');

    // Routes pour l'interface des histoires
    Route::get('/stories', function () {
        return view('app');
    })->name('stories');

    Route::get('/story/{storyId}/scene/{sceneId}', function () {
        return view('app');
    });

    // Routes du dashboard administratif - nécessite authentification et verification
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // Routes du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Logout route
Route::get('/logout', [AuthenticatedSessionController::class, 'destroy']);


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {
    // Route de test pour vérifier que l'API fonctionne
    Route::get('ping', function () {
        return response()->json(['message' => 'pong']);
    });

    // Routes d'authentification
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
        Route::post('register', function() {
            return response()->json(['message' => 'Register endpoint not implemented'], 501);
        });
    });


        // Routes pour les histoires
        Route::prefix('stories')->group(function () {
            Route::get('/', [StoryController::class, 'index']);
            Route::get('/{id}', [StoryController::class, 'show']);
            Route::post('/', [StoryController::class, 'store']);
            Route::put('/{id}', [StoryController::class, 'update']);
            Route::delete('/{id}', [StoryController::class, 'destroy']);
        });

        // Routes pour les scènes
        Route::prefix('scenes')->group(function () {
            Route::get('/first-scene', [SceneController::class, 'getFirstScene']);
            Route::get('/', [SceneController::class, 'index']);
            Route::get('/{id}', [SceneController::class, 'show']);
            Route::post('/', [SceneController::class, 'store']);
            Route::post('/{storyId}/restart', [SceneController::class, 'restart']);
            Route::put('/{id}', [SceneController::class, 'update']);
            Route::delete('/{id}', [SceneController::class, 'destroy']);
        });

        // Routes pour les choix
        Route::prefix('choices')->group(function () {
            Route::get('/', [ChoiceController::class, 'index']);
            Route::get('/{id}', [ChoiceController::class, 'show']);
            Route::get('/scene/{sceneId}', [ChoiceController::class, 'getChoicesForScene']);
            Route::post('/', [ChoiceController::class, 'store']);
            Route::post('/{id}', [ChoiceController::class, 'makeChoice']);
            Route::put('/{id}', [ChoiceController::class, 'update']);
            Route::delete('/{id}', [ChoiceController::class, 'destroy']);
        });

        // Routes pour les items
        Route::prefix('items')->group(function () {
            Route::get('/', [ItemController::class, 'index']);
            Route::get('/{id}', [ItemController::class, 'show']);
            Route::post('/', [ItemController::class, 'store']);
            Route::put('/{id}', [ItemController::class, 'update']);
            Route::delete('/{id}', [ItemController::class, 'destroy']);
        });
    
});

// Route qui renvoie une erreur 404 pour les requêtes non trouvées
Route::middleware(['auth'])->get('/{any}', [ApiController::class, 'notFoundWeb'])
    ->where('any', '.*')
    ->fallback();

require __DIR__.'/auth.php';