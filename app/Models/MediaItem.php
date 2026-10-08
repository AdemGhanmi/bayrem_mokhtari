<?php

namespace App\Models;

use App\Models\Concerns\Translates;
use Illuminate\Database\Eloquent\Model;

class MediaItem extends Model
{
    use Translates;

    protected $fillable = ['type', 'title', 'description', 'body', 'category', 'year', 'image', 'video_url', 'external_url', 'source_name', 'sort_order', 'is_active'];

    protected $casts = ['title' => 'array', 'description' => 'array', 'body' => 'array', 'is_active' => 'boolean'];


    public function embedUrl(): ?string
    {
        $url = $this->video_url ?: $this->external_url;
        if (! $url) {
            return null;
        }
        if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([A-Za-z0-9_-]{6,})~', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/'.$m[1].'?rel=0&modestbranding=1';
        }

        return null;
    }

    /** Uploaded (local) video file, as opposed to a YouTube link. */
    public function isLocalVideo(): bool
    {
        return $this->video_url && ! preg_match('~^https?://~i', $this->video_url);
    }

    /** Estimated reading time in minutes, from the current-locale body (or description). */
    public function readingTime(): int
    {
        $words = str_word_count(strip_tags($this->text('body') ?: $this->text('description')));

        return max(1, (int) ceil($words / 200));
    }
}
