<?php

namespace App\Http\Controllers\Story;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Affiche la liste des objets disponibles
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Récupération de tous les objets disponibles
        $items = Item::all();

        // Si la route commence par /api/, renvoyer du JSON
        if (str_starts_with($request->path(), 'api/')) {
            return response()->json(['items' => $items]);
        }

        // Pour une requête normale, afficher la vue
        return view('items.index', compact('items'));
    }

    /**
     * Affiche le détail d'un objet
     *
     * @param int $id Identifiant de l'objet
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        // Récupération de l'objet
        $item = Item::findOrFail($id);

        // Si la route commence par /api/, renvoyer du JSON
        if (str_starts_with($request->path(), 'api/')) {
            return response()->json(['item' => $item]);
        }

        // Pour une requête normale, afficher la vue
        return view('items.show', compact('item'));
    }
    
    /**
     * Crée un nouvel objet
     *
     * @param Request $request Données de la requête
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Création de l'item
        $item = Item::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json(['item' => $item, 'message' => 'Item créé avec succès'], 201);
    }

    /**
     * Met à jour un objet existant
     *
     * @param Request $request Données de la requête
     * @param int $id Identifiant de l'objet à modifier
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Récupération de l'objet
        $item = Item::findOrFail($id);

        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Mise à jour des données
        if ($request->has('name')) {
            $item->name = $request->name;
        }
        
        if ($request->has('description')) {
            $item->description = $request->description;
        }

        $item->save();

        return response()->json(['item' => $item, 'message' => 'Item mis à jour avec succès']);
    }

    /**
     * Supprime un objet existant
     *
     * @param int $id Identifiant de l'objet à supprimer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Récupération de l'objet
        $item = Item::findOrFail($id);

        // Suppression de l'objet
        $item->delete();

        return response()->json(['message' => 'Item supprimé avec succès']);
    }
}