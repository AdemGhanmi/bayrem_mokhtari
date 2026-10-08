<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/** One safe place for every file the dashboard stores (public/uploads/...). */
class Uploads
{
    public const IMAGE_RULE = ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'];
    public const VIDEO_RULE = ['file', 'mimes:mp4,webm,mov,ogg', 'max:102400'];

    public static function store(UploadedFile $file, string $dir): string
    {
        $ext = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
        $name = Str::uuid().'.'.$ext;
        $target = public_path("uploads/$dir");
        if (! is_dir($target)) {
            mkdir($target, 0775, true);
        }
        $file->move($target, $name);
        self::downscale("$target/$name", $ext);

        return "uploads/$dir/$name";
    }

    /** Only files inside public/uploads are ever deleted - seeded assets/ files are never touched. */
    public static function delete(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/') && ! str_contains($path, '..')) {
            $full = public_path($path);
            if (is_file($full)) {
                @unlink($full);
            }
        }
    }

    /** Shrinks huge phone photos (max 2200px wide) so pages stay fast. Needs the GD extension; silently skipped otherwise. */
    private static function downscale(string $file, string $ext, int $max = 2200): void
    {
        if (! extension_loaded('gd') || ! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return;
        }
        [$w, $h] = @getimagesize($file) ?: [0, 0];
        if ($w <= $max) {
            return;
        }
        $src = match ($ext) { 'png' => @imagecreatefrompng($file), 'webp' => @imagecreatefromwebp($file), default => @imagecreatefromjpeg($file) };
        if (! $src) {
            return;
        }
        $dst = imagescale($src, $max);
        match ($ext) { 'png' => imagepng($dst, $file, 8), 'webp' => imagewebp($dst, $file, 82), default => imagejpeg($dst, $file, 84) };
        imagedestroy($src);
        imagedestroy($dst);
    }

    /** Clears the cached public lists after any dashboard change. */
    public static function flushSiteCache(): void
    {
        foreach (['gallery', 'journal', 'video', 'diploma'] as $t) {
            Cache::forget("site.media.$t");
        }
        Cache::forget('site.career');
        Cache::forget('site.honours');
    }
}
