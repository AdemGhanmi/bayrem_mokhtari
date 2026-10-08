<?php

namespace App\Console\Commands;

use App\Models\MediaItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

/**
 * public/assets/diplomas contains every scan twice, once with a broken accent in the filename
 * ("Dipl#U251c#U2524me ..."). Broken names produce broken image URLs on some servers and doubled the gallery.
 * This command removes the duplicates (by file hash), renames the rest to diploma-01.jpg, diploma-02.jpg ...
 * and repoints the database. Safe to run many times.
 */
class NormalizeDiplomas extends Command
{
    protected $signature = 'assets:normalize-diplomas {--dry-run : Only show what would change}';

    protected $description = 'Remove duplicate diploma scans and give them clean file names';

    public function handle(): int
    {
        $dir = public_path('assets/diplomas');
        if (! is_dir($dir)) {
            $this->warn('No assets/diplomas folder, nothing to do.');

            return self::SUCCESS;
        }

        $files = array_values(array_filter(glob($dir.'/*') ?: [], fn ($f) => is_file($f) && preg_match('/\.(jpe?g|png|webp)$/i', $f)));
        $groups = [];
        foreach ($files as $f) {
            $groups[md5_file($f)][] = $f;
        }

        // Keep one file per hash; natural-sort by the first number found in the name so "…_2" comes before "…_10".
        $keep = [];
        foreach ($groups as $hash => $list) {
            usort($list, function ($a, $b) {
                $clean = fn ($x) => (int) (bool) preg_match('/^[\x20-\x7E]+$/', basename($x));
                return [$clean($b), basename($a)] <=> [$clean($a), basename($b)];
            });
            $keep[$hash] = $list[0];
        }
        uasort($keep, function ($a, $b) {
            preg_match('/(\d+)\.\w+$/', basename($a), $x);
            preg_match('/(\d+)\.\w+$/', basename($b), $y);
            return ((int) ($x[1] ?? 9999)) <=> ((int) ($y[1] ?? 9999));
        });

        $plan = [];
        $n = 1;
        foreach ($keep as $hash => $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $ext = $ext === 'jpeg' ? 'jpg' : $ext;
            $plan[$hash] = sprintf('diploma-%02d.%s', $n++, $ext);
        }

        $removed = count($files) - count($keep);
        $this->info(count($files).' files found → '.count($keep)." unique, $removed duplicate(s) to remove.");
        if ($this->option('dry-run')) {
            return self::SUCCESS;
        }

        // Map every old basename (kept or duplicate) → its new clean name, for the database.
        $map = [];
        foreach ($groups as $hash => $list) {
            foreach ($list as $f) {
                $map['assets/diplomas/'.basename($f)] = 'assets/diplomas/'.$plan[$hash];
            }
        }

        // Two-step rename (temp names first) so "diploma-01" never collides with an existing file.
        $tmp = [];
        foreach ($keep as $hash => $file) {
            $t = $dir.'/.tmp-'.$plan[$hash];
            rename($file, $t);
            $tmp[$t] = $dir.'/'.$plan[$hash];
        }
        foreach ($groups as $list) {
            foreach ($list as $f) {
                if (is_file($f)) {
                    @unlink($f);
                }
            }
        }
        foreach ($tmp as $from => $to) {
            rename($from, $to);
        }

        if (Schema::hasTable('media_items')) {
            $seen = [];
            foreach (MediaItem::where('type', 'diploma')->orderBy('id')->get() as $row) {
                $new = $map[$row->image] ?? $row->image;
                if (isset($seen[$new])) {
                    $row->delete();   // duplicate row for the same scan
                    continue;
                }
                $seen[$new] = true;
                if ($new !== $row->image) {
                    $row->update(['image' => $new]);
                }
            }
        }

        $this->info('Done. Diplomas are now '.implode(', ', array_slice(array_values($plan), 0, 3)).' …');

        return self::SUCCESS;
    }
}
