<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\Uploads;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings', ['groups' => config('site.settings'), 'values' => SiteSetting::allCached()]);
    }

    public function save(Request $r)
    {
        $locales = array_keys(config('site.locales'));
        $rules = [];
        foreach (config('site.settings') as $fields) {
            foreach ($fields as $key => $f) {
                if (! empty($f['t'])) {
                    foreach ($locales as $l) {
                        $rules["s.$key.$l"] = 'nullable|string|max:2000';
                    }
                } elseif ($f['type'] === 'image') {
                    $rules["files.$key"] = ['nullable', ...Uploads::IMAGE_RULE];
                } elseif ($f['type'] === 'url') {
                    $rules["s.$key"] = 'nullable|url|max:1000';
                } elseif ($f['type'] === 'email') {
                    $rules["s.$key"] = 'nullable|email|max:190';
                } else {
                    $rules["s.$key"] = 'nullable|string|max:2000';
                }
            }
        }
        $r->validate($rules);

        foreach (config('site.settings') as $fields) {
            foreach ($fields as $key => $f) {
                if ($f['type'] === 'image') {
                    if ($r->hasFile("files.$key")) {
                        Uploads::delete(SiteSetting::get($key));
                        SiteSetting::put($key, Uploads::store($r->file("files.$key"), 'settings'));
                    }

                    continue;
                }
                if (! empty($f['t'])) {
                    SiteSetting::put($key, collect($locales)->mapWithKeys(fn ($l) => [$l => trim((string) $r->input("s.$key.$l", ''))])->all());
                } else {
                    SiteSetting::put($key, trim((string) $r->input("s.$key", '')));
                }
            }
        }

        return back()->with('success', __('Settings saved.'));
    }
}
