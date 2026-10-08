<div class="langtabs" role="tablist" aria-label="{{ __('Language tabs') }}">
    @foreach(config('site.locales') as $code => $l)<button type="button" role="tab" class="ltab {{ $code === 'en' ? 'on' : '' }}" data-lang="{{ $code }}" aria-selected="{{ $code === 'en' ? 'true' : 'false' }}">{{ $l['name'] }}</button>@endforeach
</div>
