<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Gère les routes 404 pour l'API
     * 
     * @param Request $request La requête HTTP
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound(Request $request)
    {
        return response()->json([
            'error' => 'Route not found',
            'path' => $request->path(),
            'method' => $request->method()
        ], 404);
    }
    
    /**
     * Gère les routes 404 pour le web
     * Renvoie toujours la vue SPA pour que le routeur frontend puisse gérer l'erreur
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function notFoundWeb(Request $request)
    {
        // Pour les requêtes API/AJAX, renvoyer une réponse JSON
        if (request()->expectsJson()) {
            return $this->notFound($request);
        }

        // Pour les requêtes web normales, renvoyer l'application SPA
        // Le routeur frontend se chargera d'afficher la page d'erreur 404
        return view('app');
    }
}
