<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerEntry;
use App\Models\ContactMessage;
use App\Models\Honour;
use App\Models\MediaItem;
use App\Support\AdminResources;

class DashboardController extends Controller
{
    public function index()
    {
        $media = MediaItem::selectRaw('type, count(*) as c')->groupBy('type')->pluck('c', 'type');
        $counts = [
            'career' => CareerEntry::count(),
            'honours' => Honour::count(),
            'gallery' => $media['gallery'] ?? 0,
            'journal' => $media['journal'] ?? 0,
            'video' => $media['video'] ?? 0,
            'diploma' => $media['diploma'] ?? 0,
        ];

        return view('admin.dashboard', [
            'counts' => $counts,
            'resources' => AdminResources::all(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'messages' => ContactMessage::latest()->take(5)->get(),
            'recent' => MediaItem::whereIn('type', ['journal', 'gallery'])->latest('updated_at')->take(6)->get(),
        ]);
    }
}
