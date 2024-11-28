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
    // Ensure the correct folder is included in the content path
    $contentFilePath = 'post/' . $post->content_file_path; // Assuming 'post/' is the folder name
    $content = Storage::disk('b2')->get($contentFilePath);
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
    // Ensure the correct folder is included in the image path
    $imageFilePath = 'post/' . $post->image_path; // Assuming images are stored in 'post/' folder
    @endphp
    <img src="{{ 'https://f000.backblazeb2.com/file/' . env('B2_BUCKET_NAME') . '/' . $imageFilePath }}" alt="{{ $post->title }}">
    @endif
</div>
@endforeach
@endsection