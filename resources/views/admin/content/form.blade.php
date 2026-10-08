@extends('admin.layout')
@php($isNew = ! $item)
@section('title', ($isNew ? __('Add new') : __('Edit')).' · '.__($res['label']))
@section('content')
<div class="page-head"><div><a class="back" href="{{ route('admin.content.index', $res['key']) }}"><x-icon name="chevron"/>{{ __('Back') }}</a><h1>{{ $isNew ? __('Add new') : __('Edit') }} — {{ __($res['label']) }}</h1></div></div>
<form class="card form" method="post" enctype="multipart/form-data" action="{{ $isNew ? route('admin.content.store', $res['key']) : route('admin.content.update', [$res['key'], $item->id]) }}">
    @csrf @if(! $isNew) @method('PUT') @endif
    @if(collect($res['fields'])->contains(fn ($f) => str_starts_with($f['type'], 't_')))@include('admin.partials.langtabs')@endif
    <div class="grid">
        @foreach($res['fields'] as $f)@include('admin.partials.fields', ['f' => $f, 'item' => $item])@endforeach
        <div class="field"><label for="f-sort">{{ __('Order') }}</label><input id="f-sort" type="number" min="0" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? '') }}"></div>
        <div class="field"><label class="switch"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))><span class="slider"></span><b>{{ __('Visible on the website') }}</b></label></div>
    </div>
    <div class="form-actions"><a class="btn ghost" href="{{ route('admin.content.index', $res['key']) }}">{{ __('Cancel') }}</a><button class="btn primary" type="submit"><x-icon name="check"/>{{ __('Save') }}</button></div>
</form>
@endsection
