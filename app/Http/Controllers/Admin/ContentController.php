<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminResources;
use App\Support\Uploads;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Generic CRUD for every module declared in AdminResources (career, honours, gallery, journal, video, diploma). */
class ContentController extends Controller
{
    private function query(array $res)
    {
        $q = $res['model']::query();
        if (isset($res['type'])) {
            $q->where('type', $res['type']);
        }

        return $q;
    }

    private function find(array $res, int $id)
    {
        return $this->query($res)->findOrFail($id);
    }

    public function index(Request $r, string $type)
    {
        $res = AdminResources::get($type);
        $q = $this->query($res);

        if ($term = trim((string) $r->query('q'))) {
            $q->where(function ($w) use ($res, $term) {
                foreach ($res['search'] as $col) {
                    $w->orWhere($col, 'like', '%'.$term.'%');
                }
            });
        }
        if (in_array($r->query('status'), ['published', 'draft'], true)) {
            $q->where('is_active', $r->query('status') === 'published');
        }
        $q->orderBy('sort_order')->orderByDesc('id');

        return view('admin.content.index', [
            'res' => $res,
            'items' => $q->paginate(15)->withQueryString(),
            'total' => $this->query($res)->count(),
        ]);
    }

    public function create(string $type)
    {
        return view('admin.content.form', ['res' => AdminResources::get($type), 'item' => null]);
    }

    public function store(Request $r, string $type)
    {
        $res = AdminResources::get($type);
        $item = new $res['model'];
        if (isset($res['type'])) {
            $item->type = $res['type'];
        }
        // Newest-first modules (journal, gallery, video) put the new item on top; chronological ones (career) at the end.
        $item->sort_order = ! empty($res['new_first'])
            ? ((int) $this->query($res)->min('sort_order')) - 1
            : ((int) $this->query($res)->max('sort_order')) + 1;
        $this->fill($r, $res, $item, true);

        return redirect()->route('admin.content.index', $type)->with('success', __('Saved successfully.'));
    }

    public function edit(string $type, int $id)
    {
        $res = AdminResources::get($type);

        return view('admin.content.form', ['res' => $res, 'item' => $this->find($res, $id)]);
    }

    public function update(Request $r, string $type, int $id)
    {
        $res = AdminResources::get($type);
        $this->fill($r, $res, $this->find($res, $id), false);

        return redirect()->route('admin.content.index', $type)->with('success', __('Saved successfully.'));
    }

    public function destroy(string $type, int $id)
    {
        $res = AdminResources::get($type);
        $item = $this->find($res, $id);
        foreach ($res['fields'] as $f) {
            if (in_array($f['type'], ['image', 'video'], true)) {
                Uploads::delete($item->{$f['name'] === 'video_file' ? 'video_url' : $f['name']} ?? null);
            }
        }
        $item->delete();
        Uploads::flushSiteCache();

        return back()->with('success', __('Deleted.'));
    }

    public function toggle(string $type, int $id)
    {
        $item = $this->find(AdminResources::get($type), $id);
        $item->update(['is_active' => ! $item->is_active]);
        Uploads::flushSiteCache();

        return back()->with('success', $item->is_active ? __('Published.') : __('Moved to draft.'));
    }

    /** Drag-and-drop: receives ids in their new order. */
    public function reorder(Request $r, string $type)
    {
        $res = AdminResources::get($type);
        $ids = array_map('intval', (array) $r->input('ids', []));
        foreach ($ids as $pos => $id) {
            $this->query($res)->whereKey($id)->update(['sort_order' => $pos]);
        }
        Uploads::flushSiteCache();

        return response()->json(['ok' => true]);
    }

    private function fill(Request $r, array $res, $item, bool $creating): void
    {
        $rules = ['sort_order' => 'nullable|integer|min:0|max:9999'];
        foreach ($res['fields'] as $f) {
            $n = $f['name'];
            $req = ! empty($f['required']);
            switch ($f['type']) {
                case 't_text':
                case 't_textarea':
                    foreach (array_keys(config('site.locales')) as $l) {
                        $rules["{$n}.{$l}"] = ['nullable', 'string', 'max:'.($f['type'] === 't_text' ? 255 : 20000)];
                    }
                    if ($req) {
                        $rules[$n] = ['required', 'array'];
                    }
                    break;
                case 'image':
                    $rules[$n] = [(! empty($f['required_on_create']) && $creating && ! $item->{$n}) ? 'required' : 'nullable', ...Uploads::IMAGE_RULE];
                    break;
                case 'video':
                    $rules[$n] = ['nullable', ...Uploads::VIDEO_RULE];
                    break;
                case 'url':
                    $rules[$n] = ['nullable', 'url', 'max:1000'];
                    break;
                case 'number':
                    $rules[$n] = ['nullable', 'integer'];
                    break;
                default:
                    $rules[$n] = [$req ? 'required' : 'nullable', 'string', 'max:255'];
            }
        }
        $data = $r->validate($rules);

        // At least one language must contain the main required translatable field.
        foreach ($res['fields'] as $f) {
            if (! empty($f['required']) && str_starts_with($f['type'], 't_')) {
                $filled = collect($data[$f['name']] ?? [])->filter(fn ($v) => trim((string) $v) !== '')->isNotEmpty();
                if (! $filled) {
                    back()->withErrors([$f['name'].'.fr' => __('Please fill this field in at least one language.')])->withInput()->throwResponse();
                }
            }
        }

        foreach ($res['fields'] as $f) {
            $n = $f['name'];
            switch ($f['type']) {
                case 't_text':
                case 't_textarea':
                    $item->{$n} = collect(array_keys(config('site.locales')))
                        ->mapWithKeys(fn ($l) => [$l => trim((string) ($data[$n][$l] ?? ''))])->all();
                    break;
                case 'image':
                    if ($r->hasFile($n)) {
                        Uploads::delete($item->{$n});
                        $item->{$n} = Uploads::store($r->file($n), $f['dir'] ?? 'media');
                    } elseif ($r->boolean("remove_$n")) {
                        Uploads::delete($item->{$n});
                        $item->{$n} = null;
                    }
                    break;
                case 'video':
                    if ($r->hasFile($n)) {
                        Uploads::delete($item->video_url);
                        $item->video_url = Uploads::store($r->file($n), 'videos');
                    }
                    break;
                default:
                    if (! ($n === 'video_url' && $r->hasFile('video_file') && empty($data[$n]))) {
                        $item->{$n} = $data[$n] ?? null;
                    }
            }
        }
        if (isset($data['sort_order'])) {
            $item->sort_order = (int) $data['sort_order'];
        }
        $item->is_active = $r->boolean('is_active');
        $item->save();
        Uploads::flushSiteCache();
    }
}
