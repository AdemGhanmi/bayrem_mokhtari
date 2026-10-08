@php
    $loc = app()->getLocale(); $locales = config('site.locales');
    $unread = \App\Models\ContactMessage::where('is_read', false)->count();
    $menu = [
        'Overview' => [['admin.dashboard', 'Dashboard', 'home', 'admin.dashboard']],
        'Website' => [['admin.pages*', 'Pages', 'file', 'admin.pages'], ['admin.settings*', 'Settings', 'settings', 'admin.settings']],
        'Content' => collect(\App\Support\AdminResources::all())->map(fn ($r, $k) => ["content:$k", $r['label'], $r['icon'], ['admin.content.index', $k]])->values()->all(),
        'Inbox' => [['admin.messages*', 'Messages', 'mail', 'admin.messages']],
    ];
@endphp
<!doctype html>
<html lang="{{ $loc }}" dir="{{ $dir ?? 'ltr' }}" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow">
<title>@yield('title', __('Dashboard')) — Bayrem Admin</title>
<link rel="icon" href="{{ asset('assets/bm-mark.svg') }}" type="image/svg+xml">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('admin.css') }}?v={{ filemtime(public_path('admin.css')) }}">
<script>try{var t=localStorage.getItem('adm-theme');if(t)document.documentElement.dataset.theme=t;if(localStorage.getItem('adm-collapsed')==='1')document.documentElement.classList.add('collapsed')}catch(e){}</script>
</head>
<body class="lang-{{ $loc }}">
@include('admin.partials.sprite')
<div class="shell">
    <aside class="sidebar" id="sidebar" aria-label="{{ __('Dashboard') }}">
        <a class="logo" href="{{ route('admin.dashboard') }}"><img src="{{ asset('assets/bm-mark.svg') }}" alt="" width="34" height="34"><span><b>Bayrem</b><small>Admin</small></span></a>
        <nav>
            @foreach($menu as $group => $items)
                <p class="nav-group">{{ __($group) }}</p>
                @foreach($items as [$match, $label, $icon, $route])
                    @php
                        if (str_starts_with($match, 'content:')) { $key = substr($match, 8); $href = route('admin.content.index', $key); $active = request()->route('type') === $key; }
                        else { $href = route(is_array($route) ? $route[0] : $route); $active = request()->routeIs($match); }
                    @endphp
                    <a href="{{ $href }}" class="nav-link {{ $active ? 'active' : '' }}" title="{{ __($label) }}" @if($active) aria-current="page" @endif>
                        <x-icon :name="$icon"/><span>{{ __($label) }}</span>
                        @if($icon === 'mail' && $unread)<em class="badge">{{ $unread }}</em>@endif
                    </a>
                @endforeach
            @endforeach
        </nav>
        <a class="nav-link site-link" href="{{ route('home') }}" target="_blank" rel="noopener"><x-icon name="external"/><span>{{ __('View site') }}</span></a>
    </aside>
    <div class="scrim" id="scrim"></div>

    <div class="main">
        <header class="topbar">
            <button class="icon-btn" id="burger" type="button" aria-label="{{ __('Open menu') }}"><x-icon name="menu"/></button>
            <button class="icon-btn only-desktop" id="collapse" type="button" aria-label="{{ __('Collapse menu') }}"><x-icon name="sidebar"/></button>
            <div class="grow"></div>
            <div class="lang" role="group" aria-label="{{ __('Language') }}">@foreach($locales as $code => $l)<a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}" class="{{ $loc === $code ? 'on' : '' }}" lang="{{ $code }}">{{ $l['short'] }}</a>@endforeach</div>
            <button class="icon-btn" id="theme" type="button" aria-label="{{ __('Toggle dark mode') }}"><x-icon name="moon"/></button>
            <div class="user"><span class="avatar">{{ mb_strtoupper(mb_substr(session('admin_name', 'A'), 0, 1)) }}</span><span class="uname">{{ session('admin_name') }}</span>
                <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="icon-btn" type="submit" title="{{ __('Log out') }}" aria-label="{{ __('Log out') }}"><x-icon name="logout"/></button></form></div>
        </header>
        <main class="content">
            @yield('content')
        </main>
    </div>
</div>

<div class="toasts" aria-live="polite">
    @if(session('success'))<div class="toast ok" role="status"><x-icon name="check"/>{{ session('success') }}</div>@endif
    @if(session('error'))<div class="toast err" role="alert"><x-icon name="alert"/>{{ session('error') }}</div>@endif
    @if($errors->any())<div class="toast err" role="alert"><x-icon name="alert"/>{{ $errors->first() }}</div>@endif
</div>

<dialog id="confirm"><form method="dialog"><h3>{{ __('Are you sure?') }}</h3><p>{{ __('This cannot be undone.') }}</p><div class="dlg-actions"><button class="btn ghost" value="cancel">{{ __('Cancel') }}</button><button class="btn danger" value="ok">{{ __('Yes, delete') }}</button></div></form></dialog>
<script src="{{ asset('admin.js') }}?v={{ filemtime(public_path('admin.js')) }}" defer></script>
</body>
</html>
