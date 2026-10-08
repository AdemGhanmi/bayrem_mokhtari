@extends('site.layout')
@section('title', $item->text('title'))
@section('description', strip_tags($item->text('description')))
@php($body = $item->text('body') ?: $item->text('description'))
@section('content')
<main class="journal-detail-premium">
    <section class="journal-detail-cover">
        <div class="wrap journal-detail-cover-grid">
            <div>
                <a class="text-link back-link" href="{{ route('site.journal') }}">← {{ __('Journal') }}</a>
                <span class="editorial-kicker">{{ __(ucfirst($item->category ?: 'journal')) }}@if($item->year) · {{ $item->year }}@endif · {{ __(':n min read', ['n' => $item->readingTime()]) }}</span>
                <h1>{{ $item->text('title') }}</h1>
            </div>
            @if($item->image)<figure><x-img :src="$item->image" :alt="$item->text('title')" width="1200" height="800" eager /></figure>@endif
        </div>
    </section>
    <section class="journal-detail-body wrap">
        <article class="journal-detail-text">
            @if($item->embedUrl())<div class="journal-detail-video"><iframe src="{{ $item->embedUrl() }}" title="{{ $item->text('title') }}" loading="lazy" allowfullscreen></iframe></div>
            @elseif($item->isLocalVideo())<div class="journal-detail-video"><video controls preload="metadata" playsinline @if($item->image) poster="{{ asset($item->image) }}" @endif><source src="{{ asset($item->video_url) }}"></video></div>@endif
            @foreach(preg_split('/\R{2,}/u', $body) as $para)@if(trim($para) !== '')<p>{{ $para }}</p>@endif @endforeach
            @if($item->external_url)<p class="journal-source"><a class="text-link" href="{{ $item->external_url }}" target="_blank" rel="noopener">{{ __('Read the original source') }}@if($item->source_name) — {{ $item->source_name }}@endif ↗</a></p>@endif
        </article>
    </section>
    @if($related->isNotEmpty())
    <section class="journal-related wrap"><h2>{{ __('Related stories') }}</h2>
        <div class="bm-journal-grid">@foreach($related as $m)<a class="bm-journal-item" href="{{ route('site.journal.detail', $m->id) }}"><div class="bm-journal-media"><x-img :src="$m->image" :alt="$m->text('title')" width="1000" height="625" /></div><div class="bm-journal-info"><div><span>{{ __(ucfirst($m->category ?: 'journal')) }}</span><span>{{ $m->year }}</span></div><h3>{{ $m->text('title') }}</h3><b>{{ __('Read story') }} ↗</b></div></a>@endforeach</div>
    </section>
    @endif
</main>
@endsection
