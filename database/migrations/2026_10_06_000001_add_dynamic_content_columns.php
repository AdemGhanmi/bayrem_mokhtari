<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Every public block can now carry an eyebrow label and a button label (all translatable).
        Schema::table('page_sections', function (Blueprint $t) {
            if (! Schema::hasColumn('page_sections', 'eyebrow')) {
                $t->json('eyebrow')->nullable()->after('type');
            }
            if (! Schema::hasColumn('page_sections', 'cta')) {
                $t->json('cta')->nullable()->after('body');
            }
        });

        // Journal articles get a real long-form body, and lists get a fast composite index.
        Schema::table('media_items', function (Blueprint $t) {
            if (! Schema::hasColumn('media_items', 'body')) {
                $t->json('body')->nullable()->after('description');
            }
            $t->index(['type', 'is_active', 'sort_order'], 'media_items_list_idx');
        });
        Schema::table('career_entries', function (Blueprint $t) {
            $t->index(['is_active', 'sort_order'], 'career_entries_list_idx');
        });
    }

    public function down(): void
    {
        Schema::table('page_sections', function (Blueprint $t) {
            $t->dropColumn(['eyebrow', 'cta']);
        });
        Schema::table('media_items', function (Blueprint $t) {
            $t->dropIndex('media_items_list_idx');
            $t->dropColumn('body');
        });
        Schema::table('career_entries', function (Blueprint $t) {
            $t->dropIndex('career_entries_list_idx');
        });
    }
};
