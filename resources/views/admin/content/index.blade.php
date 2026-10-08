@extends('admin.layout')
@section('title', __($res['label']))
@php($c = $res['columns'])
@section('content')
<div class="page-head"><div><h1>{{ __($res['label']) }}</h1><p class="muted">{{ $total }} {{ __('items') }}</p></div><a class="btn primary" href="{{ route('admin.content.create', $res['key']) }}"><x-icon name="plus"/>{{ __('Add new') }}</a></div>

<form class="filters" method="get">
    <div class="search"><x-icon name="search"/><input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search…') }}" aria-label="{{ __('Search…') }}"></div>
    <select name="status" onchange="this.form.submit()" aria-label="{{ __('Status') }}"><option value="">{{ __('All statuses') }}</option><option value="published" @selected(request('status') === 'published')>{{ __('Published') }}</option><option value="draft" @selected(request('status') === 'draft')>{{ __('Draft') }}</option></select>
    <button class="btn ghost" type="submit">{{ __('Filter') }}</button>
</form>

@if($items->isEmpty())
    <div class="empty"><x-icon :name="$res['icon']"/><h3>{{ __('No results') }}</h3><p class="muted">{{ __('Try another search or add a new item.') }}</p><a class="btn primary" href="{{ route('admin.content.create', $res['key']) }}"><x-icon name="plus"/>{{ __('Add new') }}</a></div>
@else
<p class="muted hint-drag"><x-icon name="grip"/> {{ __('Drag rows to change the order') }}</p>
<div class="table" id="sortable" data-url="{{ route('admin.content.reorder', $res['key']) }}" data-token="{{ csrf_token() }}">
    @foreach($items as $it)
    <div class="trow" draggable="true" data-id="{{ $it->id }}">
        <span class="grip" aria-hidden="true"><x-icon name="grip"/></span>
        <span class="thumb">@if($it->{$c['image']})<img src="{{ asset($it->{$c['image']}) }}" alt="" loading="lazy" onerror="this.remove()">@endif</span>
        <div class="tmain"><strong>{{ \Illuminate\Support\Str::limit($it->text($c['title']), 70) ?: '—' }}</strong>
            @if($c['sub'])<small class="muted">{{ \Illuminate\Support\Str::limit($it->text($c['sub']), 90) }}</small>@endif
            <small class="tags">@foreach($c['meta'] as $mk)@if($it->{$mk})<span>{{ __(ucfirst($it->{$mk})) }}</span>@endif @endforeach</small></div>
        <form method="post" action="{{ route('admin.content.toggle', [$res['key'], $it->id]) }}">@csrf<button class="pill {{ $it->is_active ? 'on' : '' }}" type="submit" title="{{ $it->is_active ? __('Moved to draft.') : __('Published.') }}">{{ $it->is_active ? __('Published') : __('Draft') }}</button></form>
        <div class="tact">
            <a class="icon-btn" href="{{ route('admin.content.edit', [$res['key'], $it->id]) }}" title="{{ __('Edit') }}" aria-label="{{ __('Edit') }}"><x-icon name="edit"/></a>
            <form method="post" action="{{ route('admin.content.destroy', [$res['key'], $it->id]) }}" data-confirm>@csrf @method('DELETE')<button class="icon-btn danger" type="submit" title="{{ __('Delete') }}" aria-label="{{ __('Delete') }}"><x-icon name="trash"/></button></form>
        </div>
    </div>
    @endforeach
</div>
<div class="pager">{{ $items->links('admin.partials.pager') }}</div>
@endif
@endsection
