@extends('admin.layout')
@section('title', __('Messages'))
@section('content')
<div class="page-head"><div><h1>{{ __('Messages') }}</h1></div></div>
@forelse($messages as $m)
<article class="card msg {{ $m->is_read ? '' : 'unread' }}">
    <header><div><strong>{{ $m->name }}</strong> <a class="link" href="mailto:{{ $m->email }}">{{ $m->email }}</a>@if($m->subject)<div class="muted">{{ $m->subject }}</div>@endif</div><time class="muted">{{ $m->created_at->translatedFormat('d M Y, H:i') }}</time></header>
    <p>{{ $m->message }}</p>
    <footer>
        <a class="btn" href="mailto:{{ $m->email }}?subject=Re: {{ rawurlencode($m->subject ?? '') }}"><x-icon name="mail"/>{{ __('Reply by email') }}</a>
        <form method="post" action="{{ route('admin.messages.read', $m->id) }}">@csrf<button class="btn ghost" type="submit">{{ $m->is_read ? __('Mark as unread') : __('Mark as read') }}</button></form>
        <form method="post" action="{{ route('admin.messages.destroy', $m->id) }}" data-confirm>@csrf @method('DELETE')<button class="btn ghost danger" type="submit"><x-icon name="trash"/>{{ __('Delete') }}</button></form>
    </footer>
</article>
@empty<div class="empty"><x-icon name="mail"/><h3>{{ __('No messages yet') }}</h3></div>@endforelse
<div class="pager">{{ $messages->links('admin.partials.pager') }}</div>
@endsection
