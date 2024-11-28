@extends('layouts.eknexa_lay')

@section('content')
<main>
    @foreach ($posts as $post)
    <div class="yliko{{ $loop->last ? ' last' : '' }}">
        <h1>{{ $post->title }}</h1>
        @dd($post) <!-- Add this to debug the content -->
        @if ($post->image_path)
        <img src="{{ 'https://f000.backblazeb2.com/file/' . env('B2_BUCKET_NAME') . '/' . $post->image_path }}" alt="{{ $post->title }}">
        @endif
        <p>{!! nl2br($post->content) !!}</p>
    </div>
    @endforeach
</main>
@endsection