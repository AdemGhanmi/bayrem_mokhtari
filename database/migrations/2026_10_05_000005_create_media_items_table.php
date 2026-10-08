<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_items', function (Blueprint $t) {
            $t->id();
            $t->string('type');
            $t->json('title');
            $t->json('description')->nullable();
            $t->string('category')->nullable();
            $t->string('year')->nullable();
            $t->string('image')->nullable();
            $t->string('video_url')->nullable();
            $t->string('external_url')->nullable();
            $t->string('source_name')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_items');
    }
};
