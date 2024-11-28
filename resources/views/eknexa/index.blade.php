@extends('layouts.eknexa_lay')

@section('content')
<h2>Post List</h2>
@foreach ($posts as $post)
<div class="yliko{{ $loop->last ? ' last' : '' }}">
    <h1>{{ $post->title }}</h1>

    <!-- Display content text from B2 storage -->
    @if ($post->content_file_path)
    @php

    try {
    $content = Storage::disk('b2')->get($post->content_file_path);
    } catch (\Exception $e) {
    $content = 'Error loading content from Backblaze B2.';
    }
    @endphp

    <!-- Display the content  -->
    <p>{!! nl2br(e($content)) !!}</p>
    @endif

    <!-- Display image if available -->
    @if ($post->image_path)
    <img src="{{ 'https://f000.backblazeb2.com/file/' . env('B2_BUCKET_NAME') . '/' . $post->image_path }}" alt="{{ $post->title }}">
    @endif
</div>
@endforeach
@endsection