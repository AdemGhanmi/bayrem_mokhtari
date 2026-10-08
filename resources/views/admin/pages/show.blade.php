@extends('admin.layout')
@section('title', __('Pages'))
@section('content')
<div class="page-head"><div><a class="back" href="{{ route('admin.pages') }}"><x-icon name="chevron"/>{{ __('Pages') }}</a><h1>{{ $page === 'global' ? __('Global') : __(ucfirst($page)) }}</h1></div><a class="btn primary" href="{{ route('admin.pages.create', $page) }}"><x-icon name="plus"/>{{ __('Add block') }}</a></div>
<div class="table">
@forelse($sections as $s)
    <div class="trow">
        <span class="thumb">@if($s->image)<img src="{{ asset($s->image) }}" alt="" loading="lazy" onerror="this.remove()">@endif</span>
        <div class="tmain"><strong>{{ \Illuminate\Support\Str::limit($s->text('title') ?: $s->text('eyebrow') ?: $s->text('cta') ?: $s->text('body'), 70) ?: '—' }}</strong><small class="muted">{{ \Illuminate\Support\Str::limit($s->text('body'), 100) }}</small><small class="tags"><span>{{ $s->section_key }}</span></small></div>
        <span class="pill {{ $s->is_active ? 'on' : '' }}">{{ $s->is_active ? __('Show on site') : __('Hidden') }}</span>
        <div class="tact"><a class="icon-btn" href="{{ route('admin.pages.edit', [$page, $s->id]) }}" aria-label="{{ __('Edit') }}" title="{{ __('Edit') }}"><x-icon name="edit"/></a>
            <form method="post" action="{{ route('admin.pages.destroy', [$page, $s->id]) }}" data-confirm>@csrf @method('DELETE')<button class="icon-btn danger" aria-label="{{ __('Delete') }}" title="{{ __('Delete') }}"><x-icon name="trash"/></button></form></div>
    </div>
@empty<div class="empty"><x-icon name="file"/><h3>{{ __('Nothing here yet') }}</h3></div>@endforelse
</div>
@endsection
