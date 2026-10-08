<?php

namespace App\Support;

use App\Models\PageSection;
use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

/**
 * Gives the Blade views one tiny API for dashboard-editable content:
 *
 *   $page->title('hero', 'Fallback')    $page->body('intro')     $page->eyebrow('hero')
 *   $page->cta('hero')                  $page->image('hero', $fallbackPath)   $page->visible('journey')
 *   $page->setting('email')             $page->t('site_title')   (translatable setting)
 *
 * A block that was never created falls back to the default text; a block the editor
 * switched off in the dashboard is hidden (visible() === false).
 */
class PageContent
{
    private Collection $sections;

    public function __construct(public readonly string $slug)
    {
        $this->sections = PageSection::query()
            ->whereIn('page_slug', array_unique([$slug, 'global']))
            ->orderBy('sort_order')
            ->get()
            ->groupBy('page_slug');
    }

    public function section(string $key, ?string $page = null): ?PageSection
    {
        return $this->sections->get($page ?? $this->slug)?->firstWhere('section_key', $key);
    }

    /** Extra repeatable blocks, e.g. the story "method" cards (keys starting with "card-"). */
    public function cards(string $prefix = 'card-'): Collection
    {
        return ($this->sections->get($this->slug) ?? collect())
            ->filter(fn ($s) => $s->is_active && str_starts_with($s->section_key, $prefix))
            ->values();
    }

    public function visible(string $key, ?string $page = null): bool
    {
        $s = $this->section($key, $page);

        return ! $s || $s->is_active;
    }

    public function eyebrow(string $key, string $default = '', ?string $page = null): string
    {
        return $this->field($key, 'eyebrow', $default, $page);
    }

    public function title(string $key, string $default = '', ?string $page = null): string
    {
        return $this->field($key, 'title', $default, $page);
    }

    /** Title with its last word wrapped in <em> (styled gold by the theme). Output is escaped. */
    public function heading(string $key, string $default = '', ?string $page = null): HtmlString
    {
        $words = preg_split('/\s+/u', trim($this->title($key, $default, $page)), -1, PREG_SPLIT_NO_EMPTY);
        if (count($words) < 2) {
            return new HtmlString(e($words[0] ?? ''));
        }
        $last = array_pop($words);

        return new HtmlString(e(implode(' ', $words)).' <em>'.e($last).'</em>');
    }

    public function body(string $key, string $default = '', ?string $page = null): string
    {
        return $this->field($key, 'body', $default, $page);
    }

    public function cta(string $key, string $default = '', ?string $page = null): string
    {
        return $this->field($key, 'cta', $default, $page);
    }

    public function link(string $key, string $default = '', ?string $page = null): string
    {
        return $this->section($key, $page)?->link_url ?: $default;
    }

    public function image(string $key, string $fallback = ''): string
    {
        return $this->section($key)?->image ?: $fallback;
    }

    private function field(string $key, string $field, string $default, ?string $page): string
    {
        $text = $this->section($key, $page)?->text($field) ?? '';

        return $text !== '' ? $text : ($default !== '' ? __($default) : '');
    }

    /* ---- global settings ---- */

    public function setting(string $key, $default = null)
    {
        $v = SiteSetting::get($key);

        return ($v === null || $v === '' || is_array($v)) ? $default : $v;
    }

    /** Translatable setting in the current locale (with language fallback). */
    public function t(string $key, string $default = ''): string
    {
        $v = SiteSetting::get($key);
        if (! is_array($v)) {
            return $v !== null && $v !== '' ? (string) $v : ($default !== '' ? __($default) : '');
        }
        foreach ([app()->getLocale(), 'fr', 'en', 'ar'] as $l) {
            if (! empty($v[$l])) {
                return (string) $v[$l];
            }
        }

        return $default !== '' ? __($default) : '';
    }
}
