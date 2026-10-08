{{-- Renders one field definition. $f = field array, $item = model|null --}}
@php
    $n = $f['name']; $locales = config('site.locales'); $type = $f['type'];
    $val = $item?->{$n};
    $req = ! empty($f['required']);
@endphp
<div class="field {{ in_array($type, ['t_textarea','textarea']) ? 'wide' : '' }} {{ in_array($type, ['image','video']) ? 'wide' : '' }}">
    <label for="f-{{ $n }}{{ str_starts_with($type, 't_') ? '-'.array_key_first($locales) : '' }}">{{ __($f['label']) }}@if($req)<i class="req" title="{{ __('Required') }}">*</i>@endif</label>
    @if(str_starts_with($type, 't_'))
        @foreach($locales as $code => $l)
            <div class="tl" data-lang="{{ $code }}" @if($code !== 'en') hidden @endif>
                @if($type === 't_textarea')
                    <textarea id="f-{{ $n }}-{{ $code }}" name="{{ $n }}[{{ $code }}]" rows="{{ $f['rows'] ?? 5 }}" dir="{{ $l['dir'] }}" lang="{{ $code }}">{{ old("$n.$code", $val[$code] ?? '') }}</textarea>
                @else
                    <input id="f-{{ $n }}-{{ $code }}" name="{{ $n }}[{{ $code }}]" value="{{ old("$n.$code", $val[$code] ?? '') }}" dir="{{ $l['dir'] }}" lang="{{ $code }}">
                @endif
                @error("$n.$code")<p class="err">{{ $message }}</p>@enderror
            </div>
        @endforeach
    @elseif($type === 'textarea')
        <textarea id="f-{{ $n }}" name="{{ $n }}" rows="{{ $f['rows'] ?? 5 }}">{{ old($n, $val) }}</textarea>
    @elseif($type === 'image')
        <div class="img-field">
            @if($val)<div class="img-preview"><img src="{{ asset($val) }}" alt="" loading="lazy"></div>@endif
            <div><input id="f-{{ $n }}" type="file" name="{{ $n }}" accept="image/jpeg,image/png,image/webp,image/gif" data-preview>
                <small class="hint">JPG, PNG, WebP — max 8 MB</small>
                @if($val)<label class="check"><input type="checkbox" name="remove_{{ $n }}" value="1"> {{ __('Remove image') }}</label>@endif</div>
        </div>
    @elseif($type === 'video')
        <input id="f-{{ $n }}" type="file" name="{{ $n }}" accept="video/mp4,video/webm,video/quicktime">
    @else
        <input id="f-{{ $n }}" type="{{ in_array($type, ['url','number']) ? $type : 'text' }}" name="{{ $n }}" value="{{ old($n, $type === 'url' && $val && ! preg_match('~^https?://~', $val) ? '' : $val) }}" @if(! empty($f['datalist'])) list="dl-{{ $n }}" @endif @if($req) required @endif>
        @if(! empty($f['datalist']))<datalist id="dl-{{ $n }}">@foreach($f['datalist'] as $o)<option value="{{ $o }}">@endforeach</datalist>@endif
    @endif
    @if(! empty($f['hint']))<small class="hint">{{ __($f['hint']) }}</small>@endif
    @if(! str_starts_with($type, 't_'))@error($n)<p class="err">{{ $message }}</p>@enderror @endif
</div>
