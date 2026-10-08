@extends('site.layout')
@section('title', $page->title('hero', "Let's talk"))
@section('description', strip_tags($page->body('hero', 'For coaching, football analysis, media, speaking or professional enquiries.')))
@php
    $email = $page->setting('email', 'elitesport.tn@gmail.com');
    $tiles = [
        ['instagram', 'Instagram', __('Follow Bayrem'), $page->setting('instagram_handle', '@bayremmokhtari_officiel'), '<rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4.2"></circle><circle cx="17.4" cy="6.7" r="1"></circle>'],
        ['email', 'Email', __('Write directly'), $email, '<rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m4 7 8 6 8-6"></path>'],
        ['youtube', 'YouTube', __('Watch the work'), 'Coach Bayrem Mokhtari', '<path d="M21 12s0-3.4-.4-4.9a2.5 2.5 0 0 0-1.7-1.7C17.4 5 12 5 12 5s-5.4 0-6.9.4a2.5 2.5 0 0 0-1.7 1.7C3 8.6 3 12 3 12s0 3.4.4 4.9a2.5 2.5 0 0 0 1.7 1.7C6.6 19 12 19 12 19s5.4 0 6.9-.4a2.5 2.5 0 0 0 1.7-1.7C21 15.4 21 12 21 12Z"></path><path d="m10 9 5 3-5 3V9Z"></path>'],
        ['linkedin', 'LinkedIn', __('Professional enquiries'), 'Bayrem Mokhtari', '<path d="M6 9v9M6 6.2v.1M10 18v-5a3 3 0 0 1 6 0v5M10 10v8"></path>'],
    ];
@endphp
@section('content')
<main class="bm-contact-page">
    <header class="bm-contact-hero wrap" aria-labelledby="bm-contact-title">
        <div class="bm-contact-intro"><div class="bm-contact-kicker">{{ $page->eyebrow('hero', '06 / GET IN TOUCH') }}</div><h1 id="bm-contact-title" class="bm-contact-title">{{ $page->heading('hero', "Let's talk") }}</h1><p>{{ $page->body('hero', 'For coaching, football analysis, media, speaking or professional enquiries.') }}</p></div>
        <figure class="bm-contact-portrait"><x-img :src="$page->setting('contact_image', $page->setting('hero_image', 'assets/images/profile.png'))" :alt="__('Bayrem Mokhtari, football coach and analyst')" width="1000" height="1250" eager /><figcaption><strong>BAYREM MOKHTARI</strong><span>{{ $page->t('location', 'El Kef · Tunisia') }}</span></figcaption></figure>
    </header>

    <section class="bm-contact-links wrap reveal" aria-label="{{ __('Direct contact channels') }}"><div class="bm-contact-grid">
        @foreach($tiles as [$key, $label, $verb, $value, $icon])
            @php($href = $key === 'email' ? ($email ? 'mailto:'.$email : null) : $page->setting($key))
            @if($href)<a class="bm-contact-tile" href="{{ $href }}" @if($key !== 'email') target="_blank" rel="noopener noreferrer" @endif><span class="bm-contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24">{!! $icon !!}</svg></span><span class="bm-contact-label">{{ strtoupper($label) }}</span><h2>{{ $verb }} <span class="bm-contact-arrow"></span></h2><span class="bm-contact-value">{{ $value }}</span></a>@endif
        @endforeach
    </div></section>

    @if($page->visible('form'))
    <section class="bm-contact-form-section wrap reveal" aria-labelledby="bm-contact-form-title">
        <div class="bm-contact-form-copy"><div class="bm-contact-kicker">{{ $page->eyebrow('form', 'SEND A MESSAGE') }}</div><h2 id="bm-contact-form-title">{{ $page->heading('form', 'Have a project in mind?') }}</h2><p>{{ $page->body('form', 'Write directly. Every professional message is reviewed.') }}</p></div>
        <form class="bm-contact-form" method="post" action="{{ route('contact.send') }}" novalidate>
            @csrf
            @if(session('success'))<p class="bm-contact-success" role="status">{{ session('success') }}</p>@endif
            @if($errors->any())<div class="bm-contact-errors" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
            <input type="text" name="website" tabindex="-1" autocomplete="off" class="hp-field" aria-hidden="true">
            <div class="bm-contact-form-row"><label><span>{{ __('Name') }}</span><input name="name" value="{{ old('name') }}" autocomplete="name" required placeholder="{{ __('Your name') }}"></label><label><span>{{ __('Email') }}</span><input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required placeholder="{{ __('Your email') }}"></label></div>
            <label><span>{{ __('Subject') }}</span><input name="subject" value="{{ old('subject') }}"></label>
            <label><span>{{ __('Message') }}</span><textarea name="message" rows="7" required placeholder="{{ __('Your message') }}">{{ old('message') }}</textarea></label>
            <button class="bm-contact-submit" type="submit">{{ $page->cta('form', 'SEND MESSAGE') }} <span></span></button>
        </form>
    </section>
    @endif
</main>
@endsection
