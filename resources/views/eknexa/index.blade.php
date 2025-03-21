@extends('layouts.eknexa_lay')

@if(env('APP_ENV') === 'production')
<meta name="robots" content="noindex, nofollow">
@endif

@section('content')
<h2>Post List</h2>
@foreach ($posts->sortByDesc('created_at') as $post)
<div class="yliko{{ $loop->last ? ' last' : '' }}">
    <h2>{{ $post->title }}</h2>

    <p><small>Created at: {{ $post->created_at->format('d/m/Y, H:i') }}</small></p>

    @if ($post->image_path)
    @php
    $imageUrl = $post->image_path; // This is already the friendly URL
    @endphp
    <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="post_img">
    @endif

    @if ($post->content_file_path)
    @php
    try {
    $content = file_get_contents($post->content_file_path);
    } catch (\Exception $e) {
    $content = 'Error loading content from Backblaze B2.';
    }
    @endphp

    <p>{!! nl2br(e($content)) !!}</p>
    @endif
</div>
@endforeach
@endsection