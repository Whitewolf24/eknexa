@extends('layouts.eknexa_lay')

@section('content')
<main>
    @foreach ($posts as $post)
    <div class="yliko{{ $loop->last ? ' last' : '' }}">
        <h1>{{ $post->title }}</h1>
        @if ($post->image_path)
        <!-- Use the public URL directly -->
        <img src="{{ 'https://f000.backblazeb2.com/file/' . env('B2_BUCKET_NAME') . '/' . $post->image_path }}" alt="{{ $post->title }}">
        <p>{!! nl2br(e($post->content)) !!}</p>
        @endif
    </div>
    @endforeach
</main>
@endsection