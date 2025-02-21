@extends('layouts.eknexa_lay_form')

@section('content')
<form action="{{ url('/create') }}" method="POST" id="form" enctype="multipart/form-data">
    @csrf
    <div id="title">
        <label>Title:</label>
        <input type="text" name="title" value="{{ old('title') }}">
        @error('title') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div id="content">
        <label>Content:</label>
        <textarea name="content">{{ old('content') }}</textarea>
        @error('content') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div id="image">
        <label>Image:</label>
        <input type="file" name="img_upload" id="img_upload">
        @error('img_upload') <div class="error">{{ $message }}</div> @enderror
    </div>
    <button id="create" type="submit">Create Post</button>
</form>
@endsection

<script>
    document.getElementById('form').addEventListener('submit', function(event) {
        const choose_file = document.getElementById('img_upload');
        const file = choose_file.files[0];

        if (file) {

            if (file.size > 10 * 1024 * 1024) {
                alert("Παρακαλώ επιλέξτε μια εικόνα με μέγεθος έως 10MB.");
                event.preventDefault(); 
                return;
            }

            const types = ['image/jpeg', 'image/jpg', 'image/webp', 'image/png'];
            if (!types.includes(file.type)) {
                alert("Παρακαλώ επιλέξτε μια έγκυρη εικόνα (jpg, jpeg, webp, png).");
                event.preventDefault(); 
            }
        }
    });
</script>