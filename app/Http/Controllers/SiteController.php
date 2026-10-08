<?php

namespace App\Http\Controllers;

use App\Models\CareerEntry;
use App\Models\ContactMessage;
use App\Models\Honour;
use App\Models\MediaItem;
use App\Support\PageContent;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    // Eloquent collections are not cached on purpose (Laravel 12 refuses to unserialize objects from cache);
    // these are tiny indexed queries, and the settings array - the expensive repeated one - IS cached.
    private array $memo = [];

    private function media(string $type)
    {
        return $this->memo["m$type"] ??= MediaItem::where('type', $type)
            ->where('is_active', true)->orderBy('sort_order')->orderByDesc('id')->get();
    }

    private function career()
    {
        return $this->memo['career'] ??= CareerEntry::where('is_active', true)->orderBy('sort_order')->get();
    }

    private function honours()
    {
        return $this->memo['honours'] ??= Honour::where('is_active', true)->orderBy('sort_order')->get();
    }

    private function base(string $slug): array
    {
        return ['page' => new PageContent($slug)];
    }

    public function home()
    {
        return view('site.home', $this->base('home') + [
            'career' => $this->career(),
            'gallery' => $this->media('gallery'),
            'journal' => $this->media('journal'),
        ]);
    }

    public function story()
    {
        return view('site.story', $this->base('story') + ['career' => $this->career()]);
    }

    public function careerPage()
    {
        return view('site.career', $this->base('career') + [
            'career' => $this->career(),
            'honours' => $this->honours(),
            'diplomas' => $this->media('diploma'),
        ]);
    }

    public function journalPage()
    {
        return view('site.journal', $this->base('journal') + ['journal' => $this->media('journal')]);
    }

    public function galleryPage()
    {
        return view('site.gallery', $this->base('gallery') + [
            'gallery' => $this->media('gallery'),
            'videos' => $this->media('video'),
        ]);
    }

    public function contact()
    {
        return view('site.contact', $this->base('contact'));
    }

    public function careerDetail(int $id)
    {
        $entry = CareerEntry::where('is_active', true)->findOrFail($id);
        $all = $this->career();
        $i = $all->search(fn ($c) => $c->id === $entry->id);

        return view('site.career-detail', $this->base('career') + [
            'entry' => $entry,
            'prev' => $i > 0 ? $all[$i - 1] : null,
            'next' => $all[$i + 1] ?? null,
        ]);
    }

    public function journalDetail(int $id)
    {
        $item = MediaItem::where('type', 'journal')->where('is_active', true)->findOrFail($id);
        $related = $this->media('journal')->where('id', '!=', $item->id)
            ->sortByDesc(fn ($m) => $m->category === $item->category)->take(3)->values();

        return view('site.journal-detail', $this->base('journal') + ['item' => $item, 'related' => $related]);
    }

    public function sendContact(Request $r)
    {
        // Honeypot: bots fill the hidden "website" field.
        if ($r->filled('website')) {
            return back();
        }

        $data = $r->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:190',
            'subject' => 'nullable|string|max:190',
            'message' => 'required|string|min:10|max:5000',
        ]);
        ContactMessage::create($data);

        return back()->with('success', __('Message sent successfully. Thank you!'));
    }
}
