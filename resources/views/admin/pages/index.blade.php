@extends('admin.layout')
@section('title', __('Pages'))
@section('content')
<div class="page-head"><div><h1>{{ __('Pages') }}</h1><p class="muted">{{ __('Edit page blocks') }}</p></div></div>
<div class="cards">
@php($urls = ['home' => url('/'), 'story' => url('/story'), 'career' => url('/career'), 'journal' => url('/journal'), 'gallery' => url('/gallery'), 'contact' => url('/contact'), 'global' => url('/')])
@foreach($pages as $slug => $keys)
    <a class="card page-card" href="{{ route('admin.pages.show', $slug) }}"><span class="stat-ico"><x-icon name="file"/></span><h3>{{ $slug === 'global' ? __('Global') : __(['home' => 'Home', 'story' => 'Story', 'career' => 'Career', 'journal' => 'Journal', 'gallery' => 'Gallery', 'contact' => 'Contact'][$slug]) }}</h3><p class="muted">{{ $counts[$slug] ?? 0 }} {{ __('Blocks') }}</p></a>
@endforeach
</div>
@endsection
