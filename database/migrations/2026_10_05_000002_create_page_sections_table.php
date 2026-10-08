<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $t) {
            $t->id();
            $t->string('page_slug');
            $t->string('section_key');
            $t->string('type')->default('text');
            $t->json('title')->nullable();
            $t->json('body')->nullable();
            $t->string('image')->nullable();
            $t->string('video_url')->nullable();
            $t->string('link_url')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->unique(['page_slug', 'section_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
