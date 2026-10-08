@extends('site.layout')
@section('title', '')
@section('description', strip_tags($page->body('hero', $page->t('meta_description'))))
@php
    $heroImg = $page->setting('hero_image', 'assets/images/profile.png');
    $featured = $career->values();
    $iconic = $gallery->take(3)->values();
    $latest = $journal->take(3)->values();
    $storyImage = $gallery->firstWhere('image', 'assets/images/coach-pointing.png') ?? $gallery->first();
    $stats = [
        [$career->count(), 'Documented career chapters'],
        [$career->pluck('country')->filter()->unique()->count(), 'Football countries / markets'],
        [$gallery->count(), 'Images in the visual archive'],
        [$journal->count(), 'Published features and stories'],
    ];
    $embed = $page->setting(
        'youtube_embed',
        'https://www.youtube-nocookie.com/embed/Ygppq3yjyT0?rel=0&modestbranding=1',
    );
@endphp
@section('content')
    <main class="hm-home">
        @if ($page->visible('hero'))
            <section class="hm-hero" aria-labelledby="hm-hero-title">
                <div class="hm-hero-media"><x-img :src="$heroImg" :alt="__('Coach Bayrem Mokhtari on the football touchline')" width="1600" height="1100" eager />
                </div>
                <div class="hm-hero-copy wrap">
                    <div class="hm-kicker">{{ $page->eyebrow('hero', 'BAYREM MOKHTARI') }}</div>
                    <h1 id="hm-hero-title">
                        {{ $page->t('hero_line1', 'Read') }}<br>{{ $page->t('hero_line2', 'the game.') }}<br><em>{{ $page->t('hero_line3', 'Lead it.') }}</em>
                    </h1>
                    <p>{{ $page->t('hero_subtitle') ?: $page->body('hero') }}</p>
                </div>
                <a class="hm-scroll" href="#legacy"><span></span> {{ __('Scroll to explore') }}</a>
          
            </section>
        @endif

        <section id="legacy" class="hm-stats wrap reveal" aria-label="{{ __('Career numbers') }}">
            @foreach ($stats as [$n, $label])
                <div class="hm-stat"><span class="hm-stat-value"><span
                            data-count="{{ $n }}">{{ str_pad($n, 2, '0', STR_PAD_LEFT) }}</span><span
                            class="hm-stat-dot" aria-hidden="true"></span></span><span>{{ __($label) }}</span></div>
            @endforeach
        </section>

        @if ($page->visible('iconic'))
            <section class="hm-iconic wrap reveal" aria-labelledby="hm-iconic-title">
                <div class="hm-section-kicker">{{ $page->eyebrow('iconic', '01 · ICONIC MOMENTS') }}</div>
                <div class="hm-heading-row">
                    <h2 id="hm-iconic-title">{{ $page->heading('iconic', 'Only Bayrem.') }}</h2>
                    <p>{{ $page->body('iconic', 'Moments from a career lived between the touchline, the training ground and the world of football analysis.') }}
                    </p>
                </div>
                @if ($iconic->isNotEmpty())
                    <div class="hm-iconic-grid">
                        @foreach ($iconic as $i => $item)
                            <a href="{{ route('site.gallery') }}"
                                class="hm-media-card hm-media-card-{{ $i + 1 }}"><x-img :src="$item->image"
                                    :alt="$item->text('title')" width="1200" height="800" />
                                <div class="hm-card-shade"></div><span class="hm-media-label">0{{ $i + 1 }} ·
                                    {{ __(ucfirst($item->category ?: 'archive')) }}</span>
                                <div class="hm-media-caption">
                                    <h3>{{ $item->text('title') }}</h3><small>{{ $item->year }}</small>
                                </div><span class="hm-round-arrow"></span>
                            </a>
                        @endforeach
                    </div>
                @else<div class="hm-empty">{{ __('No media has been published yet.') }}</div>
                @endif
            </section>
        @endif

        @if ($page->visible('journey'))
            <section class="hm-journey wrap reveal" aria-labelledby="hm-journey-title">
                <div class="hm-section-kicker">{{ $page->eyebrow('journey', '02 · THE JOURNEY') }}</div>
                <div class="hm-heading-row">
                    <h2 id="hm-journey-title">{{ $page->heading('journey', 'Great clubs. One path.') }}</h2><a
                        class="hm-text-link" href="{{ route('site.career') }}">{{ $page->cta('journey', 'VIEW CAREER') }}
                        →</a>
                </div>
                @if ($career->isNotEmpty())
                    <div class="hm-badges">
                        @foreach ($career as $entry)
                            <div class="hm-badge"><x-img :src="$entry->logo" :alt="$entry->text('club')" width="64"
                                    height="64" /><strong>{{ $entry->text('club') }}</strong></div>
                        @endforeach
                    </div>
                    @php($first = $featured->first())
                    <div class="hm-chapters" data-hm-chapters data-chapter-label="{{ __('The chapter') }}">
                        <div class="hm-tabs" role="tablist" aria-label="{{ __('Career chapters') }}" style="--hm-chapter-count: {{ $featured->count() }}">
                            @foreach ($featured as $i => $entry)
                                @php($img = $gallery->get($i)?->image ?? ($storyImage?->image ?? $heroImg))
                                <button type="button" class="hm-tab{{ $i === 0 ? ' is-active' : '' }}"
                                    id="hm-tab-{{ $i }}" role="tab"
                                    aria-selected="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="hm-panel"
                                    tabindex="{{ $i === 0 ? '0' : '-1' }}" data-image="{{ asset($img) }}"
                                    data-club="{{ $entry->text('club') }}" data-period="{{ $entry->period }}"
                                    data-country="{{ __($entry->country) }}" data-role="{{ $entry->text('role') }}"
                                    data-index="{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}"><span>{{ $entry->period }}</span><strong>{{ $entry->text('club') }}</strong><b></b></button>
                            @endforeach
                        </div>
                        <div class="hm-panel-wrap">
                            <div id="hm-panel" class="hm-panel" role="tabpanel" tabindex="0" aria-labelledby="hm-tab-0">
                                <x-img :src="$gallery->first()?->image ?? $heroImg" :alt="$first->text('club')" width="1200" height="780" /><span
                                    class="hm-panel-caption">{{ __('The first chapter') }} ·
                                    {{ $first->period }}</span><strong class="hm-panel-counter">01 /
                                    {{ str_pad($featured->count(), 2, '0', STR_PAD_LEFT) }}</strong></div>
                            <div class="hm-panel-summary" aria-live="polite"><span
                                    class="hm-panel-country">{{ __($first->country) }}</span><strong
                                    class="hm-panel-title">{{ $first->text('role') }}</strong><span
                                    class="hm-panel-club">{{ $first->text('club') }}</span></div>
                        </div>
                    </div>
                @else<div class="hm-empty">{{ __('No career chapters have been published yet.') }}</div>
                @endif
            </section>
        @endif

        @if ($page->visible('man'))
            <section class="hm-man reveal" aria-labelledby="hm-man-title">
                <div class="wrap hm-man-grid">
                    <div class="hm-man-photo"><x-img :src="$page->section('man')?->image ?: $storyImage?->image ?? $heroImg" :alt="__('Portrait of Bayrem Mokhtari')" width="900" height="1100" />
                        <div>{{ $page->t('location', 'El Kef · Tunisia') }}</div>
                    </div>
                    <div class="hm-man-copy">
                        <div class="hm-section-kicker">{{ $page->eyebrow('man', '03 · BEYOND THE TOUCHLINE') }}</div>
                        <h2 id="hm-man-title">{{ $page->heading('man', 'The man. The mentality.') }}</h2>
                        <p>{{ \Illuminate\Support\Str::limit($page->body('man', 'Football, analysis and a career shaped by different cultures of the game.'), 220) }}
                        </p><a class="hm-text-link" href="{{ route('site.story') }}">{{ $page->cta('man', 'HIS STORY') }}
                            →</a>
                        <div class="hm-signature">Bayrem Mokhtari<small>{{ __('Football coach & analyst') }}</small></div>
                    </div>
                </div>
            </section>
        @endif

        @if ($page->visible('journal'))
            <section class="hm-journal wrap reveal" aria-labelledby="hm-journal-title">
                <div class="hm-section-kicker">{{ $page->eyebrow('journal', '04 · THE JOURNAL') }}</div>
                <div class="hm-heading-row">
                    <h2 id="hm-journal-title">{{ $page->heading('journal', 'Latest moments.') }}</h2><a
                        class="hm-text-link"
                        href="{{ route('site.journal') }}">{{ $page->cta('journal', 'ALL STORIES') }} →</a>
                </div>
                @if ($latest->isNotEmpty())
                    <div class="hm-journal-grid">
                        @foreach ($latest as $item)
                            <a class="hm-article" href="{{ route('site.journal.detail', $item->id) }}">
                                <div class="hm-article-image"><x-img :src="$item->image" :alt="$item->text('title')" width="900"
                                        height="560" /><span></span></div>
                                <div class="hm-article-meta">
                                    <small>{{ __(ucfirst($item->category ?: 'journal')) }}</small><small>{{ $item->year }}</small>
                                </div>
                                <h3>{{ $item->text('title') }}</h3>
                                <p>{{ $item->source_name }}</p>
                            </a>
                        @endforeach
                    </div>
                @else<div class="hm-empty">{{ __('No stories have been published yet.') }}</div>
                @endif
            </section>
        @endif

        @if ($page->visible('motion'))
            <section class="hm-journal wrap reveal">
                <div class="hm-motion">
                    <div class="hm-motion-video"><iframe src="{{ $embed }}"
                            title="{{ __('Coach Bayrem Mokhtari — video') }}" loading="lazy"
                            allow="accelerometer; encrypted-media; picture-in-picture; fullscreen"
                            allowfullscreen></iframe></div>
                    <div>
                        <div class="hm-section-kicker">{{ $page->eyebrow('motion', 'IN MOTION') }}</div>
                        <h3>{{ $page->title('motion', 'Watch the latest videos.') }}</h3>
                        <p>{{ $page->body('motion', 'Football analysis, coaching and commentary, straight from the YouTube channel.') }}
                        </p>
                        @if ($page->setting('youtube'))
                            <a class="hm-text-link" target="_blank" rel="noopener"
                                href="{{ $page->setting('youtube') }}">{{ $page->cta('motion', 'OPEN THE CHANNEL') }}
                                ↗</a>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        @if ($page->visible('cta'))
            <a class="hm-contact"
                href="{{ route('site.contact') }}"><span>{{ $page->cta('cta', "LET'S TALK") }}.</span><b></b></a>
        @endif
    </main>
@endsection
