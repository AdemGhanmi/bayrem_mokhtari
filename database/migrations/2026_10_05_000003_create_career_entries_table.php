<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_entries', function (Blueprint $t) {
            $t->id();
            $t->string('period');
            $t->json('club')->nullable();
            $t->json('role')->nullable();
            $t->string('country');
            $t->string('logo')->nullable();
            $t->json('description')->nullable();
            $t->string('source_url')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_entries');
    }
};
