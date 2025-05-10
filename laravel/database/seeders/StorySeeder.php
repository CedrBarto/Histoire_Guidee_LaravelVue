<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Story;
use App\Models\Scene;
use App\Models\Choice;
use App\Models\Item;
use Illuminate\Support\Facades\Schema;

class StorySeeder extends Seeder
{
    public function run()
    {
        // Version compatible avec SQLite pour désactiver les contraintes de clés étrangères
        Schema::disableForeignKeyConstraints();
        
        // Vider les tables
        Choice::truncate();
        Scene::truncate();
        Item::truncate();
        Story::truncate();
        
        // Réactiver les contraintes
        Schema::enableForeignKeyConstraints();
        
        // Charger le fichier JSON
        $json = file_get_contents(database_path('data/histoire_horreur.json'));
        $data = json_decode($json, true);
        
        // Créer une histoire
        $story = Story::create([
            'title' => 'Histoire d\'horreur',
            'description' => 'Une histoire horrifique où vous devez survivre',
            'cover_image' => 'histoire_horreur_cover.png',
        ]);
        
        // Créer les items
        foreach ($data['items'] as $itemData) {
            Item::create([
                'id' => $itemData['id'],
                'name' => $itemData['name'],
                'description' => $itemData['description']
            ]);
        }
        
        // Créer les scènes
        foreach ($data['scenes'] as $sceneData) {
            Scene::create([
                'id' => $sceneData['id'],
                'story_id' => $story->id,
                'title' => $sceneData['title'],
                'description' => $sceneData['description'],
                'image' => $sceneData['image'],
                'sound' => $sceneData['sound'],
                'is_ending' => $sceneData['is_ending'],
                'is_first' => $sceneData['id'] == 1 // Marquer la première scène
            ]);
        }
        
        // Créer les choix
        foreach ($data['scenes'] as $sceneData) {
            if (isset($sceneData['choices'])) {
                foreach ($sceneData['choices'] as $choiceData) {
                    // Gérer les récompenses d'items (unique ou multiples)
                    $primaryItemReward = null;
                    $additionalRewards = null;
                    
                    if (isset($choiceData['item_reward'])) {
                        if (is_array($choiceData['item_reward'])) {
                            // Si c'est un tableau avec des éléments
                            if (count($choiceData['item_reward']) > 0) {
                                // Premier élément comme récompense principale
                                $primaryItemReward = $choiceData['item_reward'][0];
                                
                                // S'il y a d'autres éléments, les mettre dans additional_rewards
                                if (count($choiceData['item_reward']) > 1) {
                                    $additionalRewards = array_slice($choiceData['item_reward'], 1);
                                }
                            }
                        } else {
                            // Si c'est une valeur unique
                            $primaryItemReward = $choiceData['item_reward'];
                        }
                    }
                    
                    Choice::create([
                        'scene_id' => $sceneData['id'],
                        'text' => $choiceData['text'],
                        'next_scene_id' => $choiceData['next_scene_id'],
                        'item_reward' => $primaryItemReward,
                        'additional_rewards' => $additionalRewards,
                        'type' => $choiceData['type'] ?? 'normal',
                        'answer' => $choiceData['answer'] ?? null
                    ]);
                }
            }
        }
    }
}