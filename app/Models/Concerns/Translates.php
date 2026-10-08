<?php

namespace App\Models\Concerns;

/**
 * Reads a JSON column shaped {"en":"..","fr":"..","ar":".."} in the current locale,
 * falling back to the other languages so a page is never blank.
 */
trait Translates
{
    public function text(string $field, ?string $locale = null): string
    {
        $values = $this->{$field} ?? [];
        if (! is_array($values)) {
            return (string) $values;
        }
        $locale ??= app()->getLocale();

        foreach ([$locale, 'fr', 'en', 'ar'] as $try) {
            if (isset($values[$try]) && trim((string) $values[$try]) !== '') {
                return (string) $values[$try];
            }
        }

        return '';
    }
}
