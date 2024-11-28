@extends('layouts.eknexa_lay')

@section('content')
<h2>Post List</h2>
@foreach ($posts as $post)
<div class="yliko{{ $loop->last ? ' last' : '' }}">
    <h1>{{ $post->title }}</h1>

    <!-- Display the creation date -->
    <p><small>Created at: {{ $post->created_at->format('d/m/Y, H:i') }}</small></p>

    <!-- Display content text from B2 storage via the friendly URL -->
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

    <!-- Display image if available -->
    @if ($post->image_path)
    @php

    $imageFilePath = 'images/' . $post->image_path; 
    @endphp
    <img src="{{ 'https://f000.backblazeb2.com/file/' . env('B2_BUCKET_NAME') . '/' . $imageFilePath }}" alt="{{ $post->title }}">
    @endif
</div>
@endforeach
@endsection