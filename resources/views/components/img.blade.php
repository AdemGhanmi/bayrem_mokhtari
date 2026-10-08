@props(['src' => null, 'alt' => '', 'eager' => false])
@php($fallback = asset('assets/images/placeholder.svg'))
<img src="{{ $src ? asset($src) : $fallback }}" alt="{{ $alt }}" decoding="async" @if($eager) fetchpriority="high" @else loading="lazy" @endif onerror="this.onerror=null;this.src='{{ $fallback }}';this.classList.add('is-fallback')" {{ $attributes }}>
