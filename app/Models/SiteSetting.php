<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = ['value' => 'array'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site.settings'));
        static::deleted(fn () => Cache::forget('site.settings'));
    }

    /** All settings as key => value, cached (busted automatically on save). */
    public static function allCached(): array
    {
        return Cache::rememberForever('site.settings', fn () => static::query()->pluck('value', 'key')->all());
    }

    public static function get(string $key, $default = null)
    {
        return static::allCached()[$key] ?? $default;
    }

    public static function put(string $key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
