<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('story_id')->constrained()->onDelete('cascade');
            $table->foreignId('current_scene_id')->nullable()->constrained('scenes')->nullOnDelete();
            $table->boolean('completed')->default(false);
            $table->json('collected_items')->nullable();
            $table->timestamps();
            
            // Each user can have only one progress entry per story
            $table->unique(['user_id', 'story_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};