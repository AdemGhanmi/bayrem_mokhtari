<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ $dir ?? 'ltr' }}" data-theme="dark">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>{{ __('Admin sign in') }}</title>
<link rel="icon" href="{{ asset('assets/bm-mark.svg') }}" type="image/svg+xml">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('admin.css') }}?v={{ filemtime(public_path('admin.css')) }}"></head>
<body class="login lang-{{ app()->getLocale() }}">
<main class="login-card">
    <img src="{{ asset('assets/bm-mark.svg') }}" alt="" width="56" height="56">
    <h1>{{ __('Admin sign in') }}</h1><p class="muted">{{ __('Sign in to manage the website.') }}</p>
    @if(session('error'))<p class="alert err" role="alert">{{ session('error') }}</p>@endif
    @if($errors->any())<p class="alert err" role="alert">{{ $errors->first() }}</p>@endif
    <form method="post" action="{{ route('admin.authenticate') }}">@csrf
        <div class="field"><label for="email">{{ __('Email') }}</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus></div>
        <div class="field"><label for="password">{{ __('Password') }}</label><input id="password" type="password" name="password" autocomplete="current-password" required></div>
        <button class="btn primary block" type="submit">{{ __('Sign in') }}</button>
    </form>
    <div class="lang center">@foreach(config('site.locales') as $code => $l)<a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}" class="{{ app()->getLocale() === $code ? 'on' : '' }}">{{ $l['short'] }}</a>@endforeach</div>
</main>
</body></html>
