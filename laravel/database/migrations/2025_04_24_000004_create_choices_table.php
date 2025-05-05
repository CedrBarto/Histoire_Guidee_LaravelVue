<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scene_id')->constrained()->onDelete('cascade');
            $table->string('text');
            $table->foreignId('next_scene_id')->constrained('scenes')->onDelete('cascade');
            // Item principal comme clé étrangère (peut être null)
            $table->foreignId('item_reward')->nullable()->constrained('items')->nullOnDelete();
            // Items additionnels en JSON
            $table->json('additional_rewards')->nullable();
            // Type du choix (normal ou énigme)
            $table->string('type')->default('normal');
            // Réponse pour les énigmes
            $table->string('answer')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('choices');
    }
};
