@extends('site.layout')
@section('title', $page->title('hero', 'The Story'))
@section('description', strip_tags($page->body('intro', 'A football journey built between coaching, analysis, media and different football cultures.')))
@php
    $heroImg = $page->setting('story_image', $page->setting('hero_image', 'assets/images/profile.png'));
    $portrait = $page->setting('story_portrait', 'assets/images/portrait-original.jpg');
    $cards = $page->cards();
    $cardImgs = ['assets/images/touchline-01.jpg', 'assets/images/ittihad-01.jpg', 'assets/images/nesmasprt.jpg'];
    $facts = [
        ['Known as', $page->t('fact_known_as', 'Bayrem Mokhtari')],
        ['Born', $page->t('fact_born')],
        ['Birthplace', $page->t('fact_birthplace')],
        ['Nationality', $page->t('fact_nationality')],
        ['Career chapters', (string) $career->count()],
        ['Football markets', $page->t('fact_markets')],
    ];
@endphp
@section('content')
<main class="bm-story-page">
    @if($page->visible('hero'))
    <header class="bm-story-hero" aria-labelledby="bm-story-title">
        <x-img :src="$heroImg" :alt="__('Bayrem Mokhtari, football coach and analyst')" width="1600" height="900" eager />
        <div class="bm-story-hero-copy">
            <p class="bm-story-eyebrow"><i></i><span>{{ $page->eyebrow('hero', '01 / THE STORY') }}</span></p>
            <h1 id="bm-story-title">{{ $page->heading('hero', 'The Story') }}</h1>
        </div>
    </header>
    @endif

    @if($page->visible('intro'))
    <section class="bm-story-opening wrap reveal">
        <figure class="bm-story-portrait"><x-img :src="$portrait" :alt="__('Portrait of Bayrem Mokhtari')" width="900" height="1125" /></figure>
        <div class="bm-story-prose">
            <p class="bm-story-label"><b>{{ $page->eyebrow('intro', 'EL KEF · TUNISIA') }}</b></p>
            <h2 class="bm-story-h2">{{ $page->heading('intro', 'It started with the game.') }}</h2>
            @foreach(preg_split('/\R{2,}/u', $page->body('intro', 'A football journey built between coaching, analysis, media and different football cultures.')) as $para)<p>{{ $para }}</p>@endforeach
            <dl class="bm-story-facts">
                @foreach($facts as [$label, $value])@if($value !== '')<div><dt>{{ __($label) }}</dt><dd>{{ $value }}</dd></div>@endif @endforeach
            </dl>
            <a href="{{ route('site.career') }}" class="bm-story-link"><span>{{ __('Explore career') }}</span><svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        </div>
    </section>
    @endif

    @if($cards->count() && $page->visible('method'))
    <section class="bm-story-principles wrap reveal" aria-labelledby="bm-story-mentality">
        <p class="bm-story-label" id="bm-story-mentality"><b>BM</b><i></i>{{ $page->eyebrow('method', 'THE METHOD') }}</p>
        <div class="bm-story-grid">
            @foreach($cards as $s)
            <article class="bm-story-card">
                <x-img :src="$s->image ?: $cardImgs[$loop->index % count($cardImgs)]" :alt="$s->text('title')" width="800" height="600" />
                <span class="bm-story-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <div class="bm-story-card-body"><h3>{{ $s->text('title') }}</h3><p>{{ $s->text('body') }}</p></div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    @if($career->count() && $page->visible('timeline'))
    <section class="bm-timeline wrap reveal" aria-labelledby="bm-timeline-title">
        <p class="bm-story-label"><b>{{ $page->eyebrow('timeline', 'THE TIMELINE') }}</b></p>
        <h2 id="bm-timeline-title" class="bm-story-h2">{{ $page->heading('timeline', 'Year after year.') }}</h2>
        <ol class="bm-timeline-list">
            @foreach($career as $c)
            <li><time>{{ $c->period }}</time><div class="bm-timeline-dot" aria-hidden="true"></div><a href="{{ route('site.career.detail', $c->id) }}"><strong>{{ $c->text('club') }}</strong><span>{{ $c->text('role') }}@if($c->country) · {{ __($c->country) }}@endif</span></a></li>
            @endforeach
        </ol>
    </section>
    @endif

    @if($page->visible('cta'))
    <section class="bm-story-invite"><div class="wrap"><a href="{{ route('site.contact') }}" class="bm-story-big">{{ $page->cta('cta', "LET'S TALK") }}.<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a></div></section>
    @endif
</main>
@endsection
