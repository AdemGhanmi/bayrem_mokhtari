@php
    $loc = app()->getLocale();
    $locales = config('site.locales');
    $siteTitle = $page->t('site_title', 'Coach Bayrem Mokhtari');
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? $pageTitle.' — '.$siteTitle : $siteTitle;
    $desc = trim($__env->yieldContent('description')) ?: $page->t('meta_description', 'Coach Bayrem Mokhtari — football coach, analyst and sports writer.');
    $ogImage = asset($page->setting('og_image', 'assets/images/profile.png'));
    $nav = [['site.story', 'Story'], ['site.career', 'Career'], ['site.journal', 'Journal'], ['site.gallery', 'Gallery']];
    $social = ['Instagram' => $page->setting('instagram'), 'YouTube' => $page->setting('youtube'), 'Facebook' => $page->setting('facebook'), 'LinkedIn' => $page->setting('linkedin')];
    $at = chr(64);
    $jsonLd = json_encode([$at.'context' => 'https://schema.org', $at.'type' => 'Person', 'name' => 'Bayrem Mokhtari', 'jobTitle' => __('Football coach & analyst'), 'nationality' => 'Tunisian', 'url' => url('/'), 'image' => $ogImage, 'sameAs' => array_values(array_filter($social))], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
@endphp
<!doctype html>
<html lang="{{ $loc }}" dir="{{ $dir ?? 'ltr' }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="#080807">
<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($desc), 160) }}">
<link rel="canonical" href="{{ url()->current() }}">
@foreach($locales as $code => $l)<link rel="alternate" hreflang="{{ $code }}" href="{{ url()->current().'?lang='.$code }}">@endforeach
<link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">
<meta property="og:type" content="website"><meta property="og:site_name" content="{{ $siteTitle }}"><meta property="og:title" content="{{ $fullTitle }}"><meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($desc), 200) }}"><meta property="og:image" content="{{ $ogImage }}"><meta property="og:url" content="{{ url()->current() }}"><meta property="og:locale" content="{{ $loc }}">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="{{ asset('assets/bm-mark.svg') }}" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Cairo:wght@400;600;700;800&family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('styles.css') }}?v={{ filemtime(public_path('styles.css')) }}">
<link rel="stylesheet" href="{{ asset('site-polish.css') }}?v={{ filemtime(public_path('site-polish.css')) }}">
<script type="application/ld+json">{!! $jsonLd !!}</script>
<noscript><style>.page-loader{display:none!important}.reveal{opacity:1!important;transform:none!important}</style></noscript>
</head>
<body class="lang-{{ $loc }}">
<a class="skip-link" href="#main">{{ __('Skip to content') }}</a>
<div class="page-loader" aria-hidden="true"><div class="loader-mark"><b>BM</b><span>Bayrem Mokhtari</span><div class="loader-line"><i></i></div></div></div><div class="grain" aria-hidden="true"></div><div class="cursor-glow" aria-hidden="true"></div>

<header class="site-header">
    <a class="brand" href="{{ route('home') }}" aria-label="{{ $siteTitle }}"><span class="brand-mark">BM<span>•</span></span><small>BAYREM<br>MOKHTARI</small></a>
    <nav class="main-nav" aria-label="{{ __('Main navigation') }}">
        @foreach($nav as [$route, $label])<a href="{{ route($route) }}" class="{{ request()->routeIs($route.'*') ? 'active' : '' }}" @if(request()->routeIs($route)) aria-current="page" @endif><b>{{ __($label) }}</b></a>@endforeach
    </nav>
    <div class="lang-switch" role="group" aria-label="{{ __('Language') }}">
        @foreach($locales as $code => $l)<a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}" hreflang="{{ $code }}" lang="{{ $code }}" class="{{ $loc === $code ? 'active' : '' }}" @if($loc === $code) aria-current="true" @endif title="{{ $l['name'] }}">{{ $l['short'] }}</a>@endforeach
    </div>
    <a class="header-contact" href="{{ route('site.contact') }}"><span>{{ __('Contact') }} ↗</span></a>
    <button class="menu-btn" type="button" aria-label="{{ __('Open menu') }}" aria-expanded="false" aria-controls="mobile-nav"><i></i><i></i></button>
</header>

<div class="mobile-nav" id="mobile-nav">
    @foreach(array_merge($nav, [['site.contact', 'Contact']]) as $i => [$route, $label])<a href="{{ route($route) }}" class="{{ request()->routeIs($route.'*') ? 'active' : '' }}"><span>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span><b>{{ __($label) }}</b></a>@endforeach
    <div class="mobile-lang">@foreach($locales as $code => $l)<a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}" lang="{{ $code }}" class="{{ $loc === $code ? 'active' : '' }}">{{ $l['name'] }}</a>@endforeach</div>
    <div class="mobile-social">@foreach($social as $k => $u)@if($u)<a href="{{ $u }}" target="_blank" rel="noopener">{{ $k }}</a>@endif @endforeach</div>
</div>

<div id="main">@yield('content')</div>

<button class="backtop" type="button" aria-label="{{ __('Back to top') }}">↑</button>
<footer class="site-footer">
    <div class="wrap footer-top">
        <div><div class="footer-brand">BAYREM <em>MOKHTARI.</em></div><p class="footer-muted">{{ $page->t('footer_text', 'Football · Coaching · Analysis · Media') }}</p></div>
        <div class="footer-cta"><span>{{ $page->body('footer', 'Have a project or professional enquiry?', 'global') }}</span><a href="{{ route('site.contact') }}">{{ $page->cta('footer', "LET'S TALK", 'global') }} ↗</a></div>
    </div>
    <div class="wrap footer-bottom"><span>© {{ date('Y') }} Bayrem Mokhtari</span><span>{{ $page->t('location', 'El Kef · Tunisia') }}</span><a href="{{ route('site.contact') }}">{{ __('Get in touch') }} ↗</a></div>
</footer>

<div class="lightbox" role="dialog" aria-modal="true" aria-label="{{ __('Image viewer') }}" hidden><button class="lb-close" type="button" aria-label="{{ __('Close') }}">×</button><img src="" alt=""><p class="lb-cap"></p><button class="lb-prev" type="button" aria-label="{{ __('Previous') }}">‹</button><button class="lb-next" type="button" aria-label="{{ __('Next') }}">›</button></div>
<script src="{{ asset('site.js') }}?v={{ filemtime(public_path('site.js')) }}" defer></script>
</body>
</html>
