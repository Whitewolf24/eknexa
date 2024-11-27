@extends('layouts.eknexa_lay')

@section('content')
<main>
    @foreach ($posts as $post)
    <div class="yliko{{ $loop->last ? ' last' : '' }}">
        <h1>{{ $post->title }}</h1>
        @if ($post->image_path)
        <img src="{{ Storage::disk('b2')->url($post->image_path) }}" alt="{{ $post->title }}"> @endif
        <p>{!! nl2br(e($post->content)) !!}</p>
    </div>
</main>
@endforeach
@endsection