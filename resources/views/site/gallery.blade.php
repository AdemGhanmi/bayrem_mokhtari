@extends('site.layout')
@section('title', $page->title('hero', 'In focus'))
@section('description', strip_tags($page->body('hero', 'Portraits, touchline moments, training and video.')))
@php($hero = $page->setting('gallery_image') ?: ($gallery->first()?->image ?? 'assets/images/touchline-02.jpg'))
@section('content')
<main class="bm-gallery-page">
    @if($page->visible('hero'))
    <section class="bm-gallery-hero" style="--bm-gallery-image: url('{{ asset($hero) }}')" aria-labelledby="bm-gallery-title">
        <div class="wrap bm-gallery-hero-copy"><p class="bm-gallery-eyebrow">{{ $page->eyebrow('hero', '04 / THE GALLERY') }}</p><h1 id="bm-gallery-title">{{ $page->heading('hero', 'In focus') }}</h1><p>{{ $page->body('hero', 'Portraits, touchline moments, training and video.') }}</p></div>
    </section>
    @endif
    <section class="bm-gallery-index wrap">
        <div class="bm-gallery-head"><div><p class="bm-gallery-eyebrow">{{ $page->eyebrow('index', 'THE ARCHIVE') }}</p><h2>{{ $page->heading('index', 'The images') }}</h2></div><div class="bm-gallery-count"><strong>{{ $gallery->count() + $videos->count() }}</strong><span>{{ __('Media items') }}</span></div></div>
        @if($gallery->isEmpty() && $videos->isEmpty())
            <p class="empty-note">{{ __('No media has been published yet.') }}</p>
        @else
        <div class="filters bm-gallery-filters" role="group" aria-label="{{ __('Filter by category') }}">
            <button type="button" class="filter active" data-filter="all">{{ __('All') }}</button>
            @foreach($gallery->pluck('category')->filter()->unique() as $cat)<button type="button" class="filter" data-filter="{{ $cat }}">{{ __(ucfirst($cat)) }}</button>@endforeach
            @if($videos->count())<button type="button" class="filter" data-filter="video">{{ __('Video') }}</button>@endif
        </div>
        <div class="bm-gallery-grid">
            @foreach($gallery as $m)
            <figure class="bm-gallery-item reveal" data-cat="{{ $m->category ?: 'media' }}">
                <x-img class="lightbox-trigger" tabindex="0" role="button" :src="$m->image" :alt="$m->text('title')" data-cap="{{ $m->text('title') }}" width="1200" height="900" />
                <figcaption><span>{{ __(ucfirst($m->category ?: 'media')) }}</span><strong>{{ $m->text('title') }}</strong></figcaption>
            </figure>
            @endforeach
            @foreach($videos as $m)
            <article class="bm-gallery-video reveal" data-cat="video">
                <div class="bm-gallery-video-frame">
                    @if($m->embedUrl())<iframe src="{{ $m->embedUrl() }}" title="{{ $m->text('title') }}" loading="lazy" allow="accelerometer; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                    @elseif($m->isLocalVideo())<video controls preload="metadata" playsinline @if($m->image) poster="{{ asset($m->image) }}" @endif><source src="{{ asset($m->video_url) }}"></video>
                    @else<x-img :src="$m->image" :alt="$m->text('title')" width="1200" height="675" />@endif
                </div>
                <div class="bm-gallery-video-info"><span>{{ __('Video') }} · {{ $m->source_name ?: __('Media') }}</span><h3>{{ $m->text('title') }}</h3><p>{{ $m->text('description') }}</p></div>
            </article>
            @endforeach
        </div>
        @endif
    </section>
</main>
@endsection
