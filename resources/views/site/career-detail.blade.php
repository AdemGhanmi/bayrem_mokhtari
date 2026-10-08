@extends('site.layout')
@section('title', $entry->text('club').' · '.__('Career'))
@section('description', strip_tags($entry->text('description')))
@section('content')
<main class="career-detail-page">
    <section class="career-detail-hero wrap">
        <div><a class="text-link back-link" href="{{ route('site.career') }}">← {{ __('Career') }}</a><span class="eyebrow">{{ __('Career') }}</span><h1>{{ $entry->text('club') }}</h1><p>{{ $entry->text('role') }}</p></div>
        @if($entry->logo)<figure><x-img :src="$entry->logo" :alt="$entry->text('club')" width="320" height="320" eager /></figure>@endif
    </section>
    <section class="career-detail-body wrap">
        <div class="career-detail-meta"><span>{{ $entry->period }}</span><span>{{ __($entry->country) }}</span></div>
        <div class="career-detail-text"><h2>{{ __('The chapter') }}</h2>@foreach(preg_split('/\R{2,}/u', $entry->text('description')) as $p)<p>{{ $p }}</p>@endforeach @if($entry->source_url)<a class="text-link" href="{{ $entry->source_url }}" target="_blank" rel="noopener">{{ __('Source') }} ↗</a>@endif</div>
    </section>
    <nav class="detail-pager wrap" aria-label="{{ __('Other chapters') }}">
        @if($prev)<a href="{{ route('site.career.detail', $prev->id) }}"><small>← {{ __('Previous') }}</small><strong>{{ $prev->text('club') }}</strong></a>@else<span></span>@endif
        @if($next)<a href="{{ route('site.career.detail', $next->id) }}" class="next"><small>{{ __('Next') }} →</small><strong>{{ $next->text('club') }}</strong></a>@endif
    </nav>
</main>
@endsection
