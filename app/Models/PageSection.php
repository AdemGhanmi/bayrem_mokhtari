<?php

namespace App\Models;

use App\Models\Concerns\Translates;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use Translates;

    protected $fillable = ['page_slug', 'section_key', 'type', 'eyebrow', 'title', 'body', 'cta', 'image', 'video_url', 'link_url', 'sort_order', 'is_active'];

    protected $casts = ['eyebrow' => 'array', 'cta' => 'array', 'title' => 'array', 'body' => 'array', 'is_active' => 'boolean'];

}
