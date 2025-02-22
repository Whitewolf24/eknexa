@extends('layouts.eknexa_lay_form')

@section('content')
<form action="{{ url('/create') }}" method="POST" id="form" enctype="multipart/form-data">
    @csrf
    <div id="title">
        <label>Title:</label>
        <input type="text" name="title" value="{{ old('title') }}" required>
        @error('title') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div id="content">
        <label>Content:</label>
        <textarea name="content" id="content">{{ old('content') }}</textarea>
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
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form');
        if (form) {
            form.addEventListener('submit', function(event) {
                const title = document.getElementById('title');
                const img_upload = document.getElementById('img_upload');
                const content = document.getElementById('content');
                const file = img_upload.files[0];

                // Check if title is empty
                if (!title.value.trim()) {
                    alert("Παρακαλώ συμπληρώστε τίτλο");
                    event.preventDefault(); // Prevent form submission
                    return;
                }

                // Validate image file type and size
                if (file) {
                    const valid_types = ['image/jpeg', 'image/jpg', 'image/webp', 'image/png'];
                    if (!valid_types.includes(file.type)) {
                        alert("Παρακαλώ επιλέξτε μια έγκυρη εικόνα (jpg, jpeg, webp, png).");
                        event.preventDefault(); // Prevent form submission
                        return;
                    }

                    if (file.size > 10 * 1024 * 1024) {
                        alert("Παρακαλώ επιλέξτε μια εικόνα με μέγεθος έως 10MB.");
                        event.preventDefault(); // Prevent form submission
                        return;
                    }
                }

                // Check if either content or image file is provided
                if (!file && !content.value.trim()) {
                    alert("Πρέπει να ανεβάσετε έστω κείμενο ή εικόνα");
                    event.preventDefault(); // Prevent form submission
                    return;
                }
            });
        }
    });
</script>