@extends('admin.layout')
@section('title', __('Settings'))
@section('content')
<div class="page-head"><div><h1>{{ __('Settings') }}</h1></div></div>
<form class="form" method="post" enctype="multipart/form-data" action="{{ route('admin.settings.save') }}">
    @csrf
    <div class="card">@include('admin.partials.langtabs')</div>
    @foreach($groups as $group => $fields)
    <section class="card"><h2>{{ __($group) }}</h2><div class="grid">
        @foreach($fields as $key => $f)
            @php($val = $values[$key] ?? null)
            <div class="field {{ in_array($f['type'], ['textarea', 'image']) ? 'wide' : '' }}"><label for="s-{{ $key }}{{ ! empty($f['t']) ? '-en' : '' }}">{{ __($f['label']) }}</label>
            @if(! empty($f['t']))
                @foreach(config('site.locales') as $code => $l)<div class="tl" data-lang="{{ $code }}" @if($code !== 'en') hidden @endif>
                    @if($f['type'] === 'textarea')<textarea id="s-{{ $key }}-{{ $code }}" name="s[{{ $key }}][{{ $code }}]" rows="4" dir="{{ $l['dir'] }}">{{ old("s.$key.$code", is_array($val) ? ($val[$code] ?? '') : '') }}</textarea>
                    @else<input id="s-{{ $key }}-{{ $code }}" name="s[{{ $key }}][{{ $code }}]" value="{{ old("s.$key.$code", is_array($val) ? ($val[$code] ?? '') : '') }}" dir="{{ $l['dir'] }}">@endif</div>@endforeach
            @elseif($f['type'] === 'image')
                <div class="img-field">@if(is_string($val) && $val)<div class="img-preview"><img src="{{ asset($val) }}" alt="" loading="lazy" onerror="this.remove()"></div>@endif<div><input id="s-{{ $key }}" type="file" name="files[{{ $key }}]" accept="image/jpeg,image/png,image/webp,image/gif" data-preview><small class="hint">JPG, PNG, WebP — max 8 MB</small></div></div>
            @else
                <input id="s-{{ $key }}" type="{{ in_array($f['type'], ['url', 'email']) ? $f['type'] : 'text' }}" name="s[{{ $key }}]" value="{{ old("s.$key", is_string($val) ? $val : '') }}">
            @endif
            @error("s.$key")<p class="err">{{ $message }}</p>@enderror
            </div>
        @endforeach
    </div></section>
    @endforeach
    <div class="form-actions sticky"><button class="btn primary" type="submit"><x-icon name="check"/>{{ __('Save') }}</button></div>
</form>
@endsection
