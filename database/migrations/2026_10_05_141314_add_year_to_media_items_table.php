<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The base create migration already defines `year`; only add it on older databases.
        if (! Schema::hasColumn('media_items', 'year')) {
            Schema::table('media_items', function (Blueprint $table) {
                $table->string('year')->nullable()->after('category');
            });
        }
    }

    public function down(): void
    {
        // Intentionally a no-op: `year` belongs to the base create_media_items migration.
    }
};
