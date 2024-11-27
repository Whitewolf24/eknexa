@extends('layouts.eknexa_lay')

@section('content')
<main>
    @foreach ($posts as $post)
    <div class="yliko{{ $loop->last ? ' last' : '' }}">
        <h1>{{ $post->title }}</h1>
        @if ($post->image_path)
        <img src="{{ Storage::disk('b2')->temporaryUrl($post->image_path, now()->addMinutes(5)) }}" alt="{{ $post->title }}">
        <p>{!! nl2br(e($post->content)) !!}</p>
    </div>
</main>
@endforeach
@endsection