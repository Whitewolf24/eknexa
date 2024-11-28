@extends('layouts.eknexa_lay')

@section('content')
<h2>Post List</h2>
@foreach ($posts as $post)
<div class="yliko{{ $loop->last ? ' last' : '' }}">
    <h2>{{ $post->title }}</h2>

    <!-- Display the creation date -->
    <p><small>Created at: {{ $post->created_at->format('d/m/Y, H:i') }}</small></p>

    <!-- Display image first if available -->
    @if ($post->image_path)
    @php
    $imageUrl = $post->image_path; // This is already the friendly URL
    @endphp
    <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="post_img">
    @endif

    <!-- Display content text from the file -->
    @if ($post->content_file_path)
    @php
    try {
    // Fetch the content from the friendly URL
    $content = file_get_contents($post->content_file_path);
    } catch (\Exception $e) {
    $content = 'Error loading content from Backblaze B2.';
    }
    @endphp

    <!-- Display the content -->
    <p>{!! nl2br(e($content)) !!}</p>
    @endif
</div>
@endforeach
@endsection