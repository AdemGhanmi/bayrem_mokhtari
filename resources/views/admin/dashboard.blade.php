@extends('admin.layout')
@section('title', __('Dashboard'))
@section('content')
<div class="page-head"><div><h1>{{ __('Welcome back') }}, {{ session('admin_name') }}</h1><p class="muted">{{ __('Overview') }}</p></div><a class="btn ghost" href="{{ route('home') }}" target="_blank" rel="noopener"><x-icon name="external"/>{{ __('Open site') }}</a></div>

<section class="stats">
    @foreach($resources as $key => $r)
        <a class="stat" href="{{ route('admin.content.index', $key) }}"><span class="stat-ico"><x-icon :name="$r['icon']"/></span><strong>{{ $counts[$key] ?? 0 }}</strong><span>{{ __($r['label']) }}</span></a>
    @endforeach
    <a class="stat {{ $unread ? 'hot' : '' }}" href="{{ route('admin.messages') }}"><span class="stat-ico"><x-icon name="mail"/></span><strong>{{ $unread }}</strong><span>{{ __('Messages') }} · {{ __('unread') }}</span></a>
</section>

<div class="cols">
    <section class="card"><h2>{{ __('Quick actions') }}</h2>
        <div class="quick">
            <a class="btn primary" href="{{ route('admin.content.create', 'journal') }}"><x-icon name="plus"/>{{ __('Journal') }}</a>
            <a class="btn" href="{{ route('admin.content.create', 'gallery') }}"><x-icon name="plus"/>{{ __('Gallery') }}</a>
            <a class="btn" href="{{ route('admin.content.create', 'career') }}"><x-icon name="plus"/>{{ __('Career') }}</a>
            <a class="btn" href="{{ route('admin.pages') }}"><x-icon name="file"/>{{ __('Pages') }}</a>
            <a class="btn" href="{{ route('admin.settings') }}"><x-icon name="settings"/>{{ __('Settings') }}</a>
        </div>
    </section>
    <section class="card"><div class="card-head"><h2>{{ __('Recent messages') }}</h2><a class="link" href="{{ route('admin.messages') }}">{{ __('Open') }}</a></div>
        @forelse($messages as $m)
            <div class="row-line"><span class="dot {{ $m->is_read ? '' : 'on' }}"></span><div class="grow"><strong>{{ $m->name }}</strong><small class="muted">{{ \Illuminate\Support\Str::limit($m->message, 60) }}</small></div><time class="muted">{{ $m->created_at->diffForHumans() }}</time></div>
        @empty<p class="empty-sm">{{ __('No messages yet') }}</p>@endforelse
    </section>
</div>
<section class="card"><h2>{{ __('Recently updated') }}</h2>
    <div class="recent">@forelse($recent as $it)<a class="recent-item" href="{{ route('admin.content.edit', [$it->type, $it->id]) }}"><span class="thumb">@if($it->image)<img src="{{ asset($it->image) }}" alt="" loading="lazy" onerror="this.remove()">@endif</span><span><strong>{{ \Illuminate\Support\Str::limit($it->text('title'), 40) }}</strong><small class="muted">{{ __(ucfirst($it->type)) }} · {{ $it->updated_at->diffForHumans() }}</small></span></a>@empty<p class="empty-sm">{{ __('Nothing here yet') }}</p>@endforelse</div>
</section>
@endsection
