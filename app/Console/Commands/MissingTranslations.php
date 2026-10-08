<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MissingTranslations extends Command
{
    protected $signature = 'translations:missing';

    protected $description = 'Lists every __() / trans_choice() key used in views and PHP that is missing from lang/fr.json or lang/ar.json';

    public function handle(): int
    {
        $used = [];
        foreach ([resource_path('views'), app_path()] as $dir) {
            foreach (File::allFiles($dir) as $file) {
                preg_match_all('/(?:__|trans_choice)\(\s*(["\'])((?:\\\\.|(?!\1).)*)\1/s', $file->getContents(), $m);
                foreach ($m[2] as $key) {
                    $used[stripcslashes($key)] = true;
                }
            }
        }
        // Labels declared in config/AdminResources are translated at render time too.
        foreach (config('site.settings') as $group => $fields) {
            $used[$group] = true;
            foreach ($fields as $f) {
                $used[$f['label']] = true;
            }
        }
        foreach (\App\Support\AdminResources::all() as $r) {
            $used[$r['label']] = true;
            foreach ($r['fields'] as $f) {
                $used[$f['label']] = true;
                if (! empty($f['hint'])) {
                    $used[$f['hint']] = true;
                }
            }
        }

        $missing = 0;
        foreach (['fr', 'ar'] as $loc) {
            $have = json_decode(File::get(lang_path("$loc.json")), true) ?: [];
            $gap = array_values(array_filter(array_keys($used), fn ($k) => $k !== '' && ! str_contains($k, '$') && ! isset($have[$k])));
            $this->line("<fg=cyan>$loc.json</> — ".count($gap).' missing');
            foreach ($gap as $k) {
                $this->line("  - $k");
            }
            $missing += count($gap);
        }

        return $missing ? self::FAILURE : self::SUCCESS;
    }
}
