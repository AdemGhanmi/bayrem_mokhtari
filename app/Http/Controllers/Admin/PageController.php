<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use App\Support\Uploads;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Editable blocks of every public page (Dashboard > Pages). */
class PageController extends Controller
{
    private function pageOrFail(string $page): string
    {
        abort_unless(array_key_exists($page, config('site.pages')), 404);

        return $page;
    }

    public function index()
    {
        $counts = PageSection::selectRaw('page_slug, count(*) c')->groupBy('page_slug')->pluck('c', 'page_slug');

        return view('admin.pages.index', ['pages' => config('site.pages'), 'counts' => $counts]);
    }

    public function show(string $page)
    {
        $this->pageOrFail($page);

        return view('admin.pages.show', [
            'page' => $page,
            'sections' => PageSection::where('page_slug', $page)->orderBy('sort_order')->get(),
        ]);
    }

    public function create(string $page)
    {
        $this->pageOrFail($page);

        return view('admin.pages.form', ['page' => $page, 'section' => new PageSection(['page_slug' => $page, 'is_active' => true, 'sort_order' => 50])]);
    }

    public function store(Request $r, string $page)
    {
        $this->pageOrFail($page);
        $key = $r->validate([
            'section_key' => ['required', 'regex:/^[a-z0-9\-_]+$/', 'max:60',
                Rule::unique('page_sections', 'section_key')->where('page_slug', $page)],
        ], ['section_key.regex' => __('Use only lowercase letters, numbers, - and _.')])['section_key'];

        $section = new PageSection(['page_slug' => $page, 'section_key' => $key]);

        return $this->save($r, $section);
    }

    public function edit(string $page, PageSection $section)
    {
        abort_unless($section->page_slug === $this->pageOrFail($page), 404);

        return view('admin.pages.form', ['page' => $page, 'section' => $section]);
    }

    public function update(Request $r, string $page, PageSection $section)
    {
        abort_unless($section->page_slug === $this->pageOrFail($page), 404);

        return $this->save($r, $section);
    }

    public function destroy(string $page, PageSection $section)
    {
        abort_unless($section->page_slug === $this->pageOrFail($page), 404);
        Uploads::delete($section->image);
        $section->delete();

        return redirect()->route('admin.pages.show', $page)->with('success', __('Deleted.'));
    }

    private function save(Request $r, PageSection $section)
    {
        $rules = ['image' => ['nullable', ...Uploads::IMAGE_RULE], 'link_url' => 'nullable|string|max:1000', 'sort_order' => 'nullable|integer|min:0|max:9999'];
        foreach (['eyebrow', 'title', 'body', 'cta'] as $f) {
            foreach (array_keys(config('site.locales')) as $l) {
                $rules["$f.$l"] = 'nullable|string|max:20000';
            }
        }
        $d = $r->validate($rules);

        foreach (['eyebrow', 'title', 'body', 'cta'] as $f) {
            $section->{$f} = collect(array_keys(config('site.locales')))->mapWithKeys(fn ($l) => [$l => trim((string) ($d[$f][$l] ?? ''))])->all();
        }
        $section->link_url = $d['link_url'] ?? null;
        $section->sort_order = (int) ($d['sort_order'] ?? 0);
        $section->is_active = $r->boolean('is_active');
        if ($r->hasFile('image')) {
            Uploads::delete($section->image);
            $section->image = Uploads::store($r->file('image'), 'sections');
        } elseif ($r->boolean('remove_image')) {
            Uploads::delete($section->image);
            $section->image = null;
        }
        $section->save();

        return redirect()->route('admin.pages.show', $section->page_slug)->with('success', __('Saved successfully.'));
    }
}
