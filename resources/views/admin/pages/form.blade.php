@extends('admin.layout')
@php($isNew = ! $section->exists)
@section('title', $isNew ? __('Add block') : __('Edit'))
@section('content')
@php
    $locales = config('site.locales');
    $fields = [['eyebrow', 'Eyebrow', 'input'], ['title', 'Title', 'input'], ['body', 'Text', 'textarea'], ['cta', 'Button label', 'input']];
@endphp
<div class="page-head"><div><a class="back" href="{{ route('admin.pages.show', $page) }}"><x-icon name="chevron"/>{{ __('Back') }}</a><h1>{{ $isNew ? __('Add block') : __('Edit') }} — {{ $section->section_key ?: __('Block key') }}</h1></div></div>
<form class="card form" method="post" enctype="multipart/form-data" action="{{ $isNew ? route('admin.pages.store', $page) : route('admin.pages.update', [$page, $section->id]) }}">
    @csrf @if(! $isNew) @method('PUT') @endif
    @include('admin.partials.langtabs')
    <div class="grid">
        @if($isNew)<div class="field"><label for="f-key">{{ __('Block key') }}<i class="req">*</i></label><input id="f-key" name="section_key" value="{{ old('section_key') }}" placeholder="card-example" required pattern="[a-z0-9_\-]+"><small class="hint">story: card-… → {{ __('Story') }} cards</small>@error('section_key')<p class="err">{{ $message }}</p>@enderror</div>@endif
        @foreach($fields as [$name, $label, $kind])
        <div class="field {{ $kind === 'textarea' ? 'wide' : '' }}"><label for="f-{{ $name }}-en">{{ __($label) }}</label>
            @foreach($locales as $code => $l)
            <div class="tl" data-lang="{{ $code }}" @if($code !== 'en') hidden @endif>
                @if($kind === 'textarea')<textarea id="f-{{ $name }}-{{ $code }}" name="{{ $name }}[{{ $code }}]" rows="6" dir="{{ $l['dir'] }}">{{ old("$name.$code", $section->{$name}[$code] ?? '') }}</textarea>
                @else<input id="f-{{ $name }}-{{ $code }}" name="{{ $name }}[{{ $code }}]" value="{{ old("$name.$code", $section->{$name}[$code] ?? '') }}" dir="{{ $l['dir'] }}">@endif
            </div>
            @endforeach
        </div>
        @endforeach
        @include('admin.partials.fields', ['f' => ['name' => 'image', 'label' => 'Image', 'type' => 'image'], 'item' => $section])
        <div class="field"><label for="f-link">{{ __('Link') }}</label><input id="f-link" name="link_url" value="{{ old('link_url', $section->link_url) }}"></div>
        <div class="field"><label for="f-sort">{{ __('Order') }}</label><input id="f-sort" type="number" min="0" name="sort_order" value="{{ old('sort_order', $section->sort_order) }}"></div>
        <div class="field"><label class="switch"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $section->is_active))><span class="slider"></span><b>{{ __('Show on site') }}</b></label></div>
    </div>
    <div class="form-actions"><a class="btn ghost" href="{{ route('admin.pages.show', $page) }}">{{ __('Cancel') }}</a><button class="btn primary" type="submit"><x-icon name="check"/>{{ __('Save') }}</button></div>
</form>
@endsection
