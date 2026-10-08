@extends('site.layout')
@section('title', $page->title('hero', 'The Journal'))
@section('description', strip_tags($page->body('hero', 'Articles, interviews, analysis, images and video from the football journey.')))
@php($hero = $page->setting('journal_image') ?: ($journal->first()?->image ?? 'assets/images/1535040556641.jpg'))
@section('content')
<main class="bm-journal-page">
    @if($page->visible('hero'))
    <section class="bm-journal-hero">
        <x-img :src="$hero" :alt="__('Bayrem Mokhtari')" class="bm-journal-hero-image" width="1600" height="900" eager />
        <div class="bm-journal-hero-overlay"></div>
        <div class="wrap bm-journal-hero-copy"><span class="bm-journal-eyebrow">{{ $page->eyebrow('hero', '03 / THE JOURNAL') }}</span><h1>{{ $page->heading('hero', 'The Journal') }}</h1><p>{{ $page->body('hero', 'Articles, interviews, analysis, images and video from the football journey.') }}</p></div>
    </section>
    @endif
    <section class="bm-journal-index wrap">
        <div class="bm-journal-head"><div><span class="bm-journal-eyebrow">{{ $page->eyebrow('index', 'LATEST STORIES') }}</span><h2>{{ $page->heading('index', 'The archive') }}</h2></div><span class="bm-journal-total">{{ trans_choice('{0} No stories|{1} :count story|[2,*] :count stories', $journal->count()) }}</span></div>
        @if($journal->isEmpty())
            <p class="empty-note">{{ __('No stories have been published yet.') }}</p>
        @else
        <div class="journal-filters filters" role="group" aria-label="{{ __('Filter by category') }}"><button type="button" class="filter active" data-filter="all">{{ __('All') }}</button>@foreach($journal->pluck('category')->filter()->unique() as $cat)<button type="button" class="filter" data-filter="{{ $cat }}">{{ __(ucfirst($cat)) }}</button>@endforeach</div>
        <div class="bm-journal-grid">
            @foreach($journal as $m)
            <a class="bm-journal-item reveal" data-cat="{{ $m->category ?: 'journal' }}" href="{{ route('site.journal.detail', $m->id) }}">
                <div class="bm-journal-media"><x-img :src="$m->image" :alt="$m->text('title')" width="1000" height="625" />@if($m->video_url)<span class="story-video-label">{{ __('Video') }}</span>@endif</div>
                <div class="bm-journal-info"><div><span>{{ __(ucfirst($m->category ?: 'journal')) }}</span><span>{{ $m->year }}</span></div><h3>{{ $m->text('title') }}</h3><p>{{ \Illuminate\Support\Str::limit($m->text('description'), 150) }}</p><b>{{ __('Read story') }} ↗</b></div>
            </a>
            @endforeach
        </div>
        @endif
    </section>
</main>
@endsection
