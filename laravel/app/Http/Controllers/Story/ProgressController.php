<?php

namespace App\Http\Controllers\Story;

use App\Models\Progress;
use App\Models\Scene;
use App\Models\Story;
use App\Models\Choice;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    // Get current progress for a user and story
    public function getProgress($storyId)
    {
        $userId = Auth::id();
        $progress = Progress::where('user_id', $userId)
            ->where('story_id', $storyId)
            ->first();
            
        if (!$progress) {
            return response()->json([
                'message' => 'No progress found'
            ], 404);
        }
        
        return response()->json([
            'progress' => $progress,
            'current_scene' => $progress->currentScene,
            'items' => $progress->collected_items
        ]);
    }
    
    // Start or resume a story
    public function startStory($storyId)
    {
        $userId = Auth::id();
        $story = Story::findOrFail($storyId);
        
        // Check if progress already exists
        $progress = Progress::where('user_id', $userId)
            ->where('story_id', $storyId)
            ->first();
            
        if (!$progress) {
            // Start new progress with first scene
            $firstScene = Scene::where('story_id', $storyId)
                ->where('is_first', true)
                ->first();
                
            // Fallback au cas où is_first n'est pas défini
            if (!$firstScene) {
                $firstScene = Scene::where('story_id', $storyId)
                    ->orderBy('id')
                    ->first();
            }
            
            $progress = Progress::create([
                'user_id' => $userId,
                'story_id' => $storyId,
                'current_scene_id' => $firstScene->id,
                'completed' => false,
                'collected_items' => []
            ]);
        }
        
        return response()->json([
            'progress' => $progress,
            'current_scene' => $progress->currentScene->load('choices'),
            'items' => $progress->collected_items
        ]);
    }
    
    // Make a choice and move to next scene
    public function makeChoice(Request $request, $progressId)
    {
        $userId = Auth::id();
        $progress = Progress::where('id', $progressId)
            ->where('user_id', $userId)
            ->firstOrFail();
            
        $choiceId = $request->choice_id;
        $choice = Choice::findOrFail($choiceId);
        
        // Handle riddles
        if ($choice->isRiddle()) {
            $answer = $request->answer;
            
            if (!$choice->checkAnswer($answer)) {
                return response()->json([
                    'message' => 'Incorrect answer'
                ], 400);
            }
        }
        
        // Update progress to new scene
        $progress->current_scene_id = $choice->next_scene_id;
        
        // Add item rewards if any
        if ($choice->item_reward) {
            $progress->addItem($choice->item_reward);
        }
        
        // Add additional rewards if any
        if (!empty($choice->additional_rewards)) {
            $progress->addItems($choice->additional_rewards);
        }
        
        // Check if this is an ending
        $nextScene = Scene::find($choice->next_scene_id);
        if ($nextScene && $nextScene->is_ending) {
            $progress->completed = true;
        }
        
        $progress->save();
        
        return response()->json([
            'progress' => $progress,
            'current_scene' => $progress->currentScene->load('choices'),
            'items' => $progress->collected_items
        ]);
    }
    
    // Reset progress for a story
    public function resetProgress($storyId)
    {
        $userId = Auth::id();
        
        Progress::where('user_id', $userId)
            ->where('story_id', $storyId)
            ->delete();
            
        return $this->startStory($storyId);
    }

    public function restart($progressId)
    {
        $userId = Auth::id();
        $progress = Progress::where('id', $progressId)
            ->where('user_id', $userId)
            ->firstOrFail();
            
        $storyId = $progress->story_id;
        
        // Trouver la première scène
        $firstScene = Scene::where('story_id', $storyId)
            ->where('is_first', true)
            ->first();
            
        if (!$firstScene) {
            $firstScene = Scene::where('story_id', $storyId)
                ->orderBy('id')
                ->first();
        }
        
        // Réinitialiser la progression
        $progress->update([
            'current_scene_id' => $firstScene->id,
            'completed' => false,
            'collected_items' => []
        ]);
        
        return response()->json([
            'progress' => $progress,
            'current_scene' => $progress->currentScene->load('choices'),
            'items' => $progress->collected_items
        ]);
    }

    public function getInventory($storyId)
    {
        $userId = Auth::id();
        $progress = Progress::where('user_id', $userId)
            ->where('story_id', $storyId)
            ->first();
            
        if (!$progress) {
            return response()->json([
                'message' => 'Aucune progression trouvée'
            ], 404);
        }
        
        $itemIds = $progress->collected_items ?? [];
        $items = Item::whereIn('id', $itemIds)->get();
        
        return response()->json([
            'items' => $items
        ]);
    }
}