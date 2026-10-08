<?php

namespace App\Support;

use App\Models\CareerEntry;
use App\Models\Honour;
use App\Models\MediaItem;

/**
 * Describes every dashboard module once. The generic list/form views and the
 * ContentController read this, so each module gets full CRUD from one definition.
 *
 * field types: text | number | url | textarea | image | video | t_text | t_textarea
 */
class AdminResources
{
    public static function all(): array
    {
        $title = ['name' => 'title', 'label' => 'Title', 'type' => 't_text', 'required' => true];

        return [
            'career' => [
                'model' => CareerEntry::class, 'label' => 'Career', 'single' => 'career chapter', 'icon' => 'route',
                'search' => ['club', 'country', 'period'],
                'columns' => ['image' => 'logo', 'title' => 'club', 'sub' => 'role', 'meta' => ['period', 'country']],
                'fields' => [
                    ['name' => 'club', 'label' => 'Club / team', 'type' => 't_text', 'required' => true],
                    ['name' => 'role', 'label' => 'Role', 'type' => 't_text'],
                    ['name' => 'period', 'label' => 'Period', 'type' => 'text', 'required' => true, 'hint' => 'e.g. 2009 — 2010'],
                    ['name' => 'country', 'label' => 'Country', 'type' => 'text', 'required' => true],
                    ['name' => 'description', 'label' => 'Description', 'type' => 't_textarea'],
                    ['name' => 'logo', 'label' => 'Club logo', 'type' => 'image', 'dir' => 'logos'],
                    ['name' => 'source_url', 'label' => 'Source link', 'type' => 'url'],
                ],
            ],
            'honours' => [
                'model' => Honour::class, 'label' => 'Honours', 'single' => 'honour', 'icon' => 'trophy',
                'search' => ['title', 'year'],
                'columns' => ['image' => 'image', 'title' => 'title', 'sub' => 'description', 'meta' => ['year']],
                'fields' => [
                    $title,
                    ['name' => 'description', 'label' => 'Description', 'type' => 't_textarea'],
                    ['name' => 'year', 'label' => 'Year', 'type' => 'text'],
                    ['name' => 'image', 'label' => 'Image', 'type' => 'image', 'dir' => 'honours'],
                    ['name' => 'source_url', 'label' => 'Source link', 'type' => 'url'],
                ],
            ],
            'gallery' => [
                'model' => MediaItem::class, 'label' => 'Gallery', 'single' => 'photo', 'icon' => 'image', 'type' => 'gallery', 'new_first' => true,
                'search' => ['title', 'category'],
                'columns' => ['image' => 'image', 'title' => 'title', 'sub' => null, 'meta' => ['category', 'year']],
                'fields' => [
                    $title,
                    ['name' => 'image', 'label' => 'Photo', 'type' => 'image', 'dir' => 'media', 'required_on_create' => true],
                    ['name' => 'category', 'label' => 'Category', 'type' => 'text', 'hint' => 'media, training, matchday ...', 'datalist' => ['media', 'training', 'matchday']],
                    ['name' => 'year', 'label' => 'Year', 'type' => 'text'],
                ],
            ],
            'journal' => [
                'model' => MediaItem::class, 'label' => 'Journal', 'single' => 'article', 'icon' => 'book', 'type' => 'journal', 'new_first' => true,
                'search' => ['title', 'category', 'source_name'],
                'columns' => ['image' => 'image', 'title' => 'title', 'sub' => 'description', 'meta' => ['category', 'year']],
                'fields' => [
                    $title,
                    ['name' => 'description', 'label' => 'Summary', 'type' => 't_textarea'],
                    ['name' => 'body', 'label' => 'Article text', 'type' => 't_textarea', 'rows' => 12, 'hint' => 'Separate paragraphs with a blank line.'],
                    ['name' => 'image', 'label' => 'Cover image', 'type' => 'image', 'dir' => 'media', 'required_on_create' => true],
                    ['name' => 'category', 'label' => 'Category', 'type' => 'text', 'datalist' => ['profile', 'career', 'analysis', 'matchday', 'video']],
                    ['name' => 'year', 'label' => 'Year', 'type' => 'text'],
                    ['name' => 'source_name', 'label' => 'Source name', 'type' => 'text'],
                    ['name' => 'external_url', 'label' => 'Source link', 'type' => 'url'],
                    ['name' => 'video_url', 'label' => 'YouTube link (optional)', 'type' => 'url'],
                ],
            ],
            'video' => [
                'model' => MediaItem::class, 'label' => 'Videos', 'single' => 'video', 'icon' => 'video', 'type' => 'video', 'new_first' => true,
                'search' => ['title', 'source_name'],
                'columns' => ['image' => 'image', 'title' => 'title', 'sub' => 'description', 'meta' => ['source_name']],
                'fields' => [
                    $title,
                    ['name' => 'description', 'label' => 'Description', 'type' => 't_textarea'],
                    ['name' => 'video_url', 'label' => 'YouTube link', 'type' => 'url', 'hint' => 'Or upload a file below.'],
                    ['name' => 'video_file', 'label' => 'Video file (mp4, webm)', 'type' => 'video'],
                    ['name' => 'image', 'label' => 'Poster image', 'type' => 'image', 'dir' => 'media'],
                    ['name' => 'source_name', 'label' => 'Source name', 'type' => 'text'],
                ],
            ],
            'diploma' => [
                'model' => MediaItem::class, 'label' => 'Diplomas', 'single' => 'diploma', 'icon' => 'award', 'type' => 'diploma',
                'search' => ['title'],
                'columns' => ['image' => 'image', 'title' => 'title', 'sub' => null, 'meta' => ['year']],
                'fields' => [
                    $title,
                    ['name' => 'image', 'label' => 'Scan / photo', 'type' => 'image', 'dir' => 'diplomas', 'required_on_create' => true],
                    ['name' => 'year', 'label' => 'Year', 'type' => 'text'],
                ],
            ],
        ];
    }

    public static function get(string $type): array
    {
        $all = self::all();
        abort_unless(isset($all[$type]), 404);

        return $all[$type] + ['key' => $type];
    }
}
