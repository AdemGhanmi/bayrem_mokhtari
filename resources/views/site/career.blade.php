@extends('site.layout')
@section('title', $page->title('hero', 'The Career'))
@section('description', strip_tags($page->body('hero', 'Documented career chapters across football cultures.')))
@section('content')
<main class="bm-career-page">
    @if($page->visible('hero'))
    <section class="bm-career-hero" aria-labelledby="bm-career-title">
        <x-img :src="$page->setting('career_image', 'assets/images/portrait-original.jpg')" :alt="__('Bayrem Mokhtari career portrait')" width="1600" height="900" eager />
        <div class="bm-career-hero-copy wrap"><p class="bm-career-eyebrow">{{ $page->eyebrow('hero', '02 / THE CAREER') }}</p><h1 id="bm-career-title">{{ $page->heading('hero', 'The Career') }}</h1><p>{{ $page->body('hero', 'Documented career chapters across football cultures.') }}</p></div>
    </section>
    @endif

    @if($page->visible('journey'))
    <section class="bm-career-journey wrap">
        <div class="bm-career-head"><div><p class="bm-career-eyebrow">{{ $page->eyebrow('journey', 'THE JOURNEY') }}</p><h2>{{ $page->heading('journey', 'Every club. One path.') }}</h2></div><div class="bm-career-summary"><strong>{{ $career->count() }}</strong><span>{{ __('Documented chapters') }}</span></div></div>
        <div class="career-filters" role="group" aria-label="{{ __('Filter by country') }}"><button type="button" class="cfilter active" data-c="all">{{ __('All') }}</button>@foreach($career->pluck('country')->filter()->unique() as $country)<button type="button" class="cfilter" data-c="{{ $country }}">{{ __($country) }}</button>@endforeach</div>
        @if($career->isEmpty())<p class="empty-note">{{ __('No career chapters have been published yet.') }}</p>@endif
        <div class="bm-career-list">
            @foreach($career as $index => $c)
            <article class="bm-career-row career-card reveal{{ $index >= 6 ? ' bm-career-hidden' : '' }}" data-index="{{ $index }}" data-country="{{ $c->country }}">
                <a href="{{ route('site.career.detail', $c->id) }}" class="bm-career-row-main"><time>{{ $c->period }}</time><div class="bm-career-logo"><x-img :src="$c->logo" :alt="$c->text('club')" width="72" height="72" /></div><div><h3>{{ $c->text('club') }}</h3><p>{{ $c->text('role') }} · {{ __($c->country) }}</p><small>{{ \Illuminate\Support\Str::limit($c->text('description'), 140) }}</small></div><span aria-hidden="true">↗</span></a>
            </article>
            @endforeach
        </div>
        @if($career->count() > 6)<button class="bm-career-more" type="button" data-more="{{ __('Show more') }}" data-less="{{ __('Show less') }}">{{ __('Show more') }}</button>@endif
    </section>
    @endif

    @if($page->visible('records'))
    <section class="bm-career-archive"><div class="wrap">
        <div class="bm-career-head"><div><p class="bm-career-eyebrow">{{ $page->eyebrow('records', 'THE RECORD') }}</p><h2>{{ $page->heading('records', 'Honours & diplomas') }}</h2></div><p class="bm-career-note">{{ $page->body('records', 'Professional highlights and coaching qualifications, presented as part of the career.') }}</p></div>
        <div class="bm-career-honours">@foreach($honours as $h)<article class="bm-career-honour reveal"><span>{{ $h->year ?: '—' }}</span><h3>{{ $h->text('title') }}</h3><p>{{ $h->text('description') }}</p></article>@endforeach</div>
        @if($diplomas->count())
        <div class="bm-career-documents"><strong>{{ $diplomas->count() }}</strong><span>{{ __('Documents') }}</span></div>
        <div class="bm-career-diplomas">@foreach($diplomas as $d)<figure class="bm-career-diploma reveal"><x-img class="lightbox-trigger" tabindex="0" :src="$d->image" :alt="$d->text('title')" data-cap="{{ $d->text('title') }}" width="800" height="600" /><figcaption>{{ $d->text('title') }}</figcaption></figure>@endforeach</div>
        @endif
    </div></section>
    @endif
</main>
@endsection
