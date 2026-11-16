<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $note->title }}</title>
</head>
<body>
    <main>
        <h1>{{ $note->title }}</h1>
        <p>{{ $note->body }}</p>

        @if ($note->attachment_path)
            <p><a href="{{ route('notes.download', $note) }}">Download the attachment</a></p>
        @endif

        <form method="POST" action="{{ route('notes.attach', $note) }}" enctype="multipart/form-data">
            @csrf
            <label for="attachment">Attach a PDF, PNG or JPG (up to 2 MB)</label>
            <input id="attachment" type="file" name="attachment" accept=".pdf,.png,.jpg" required>
            @error('attachment') <span>{{ $message }}</span> @enderror
            <button type="submit">Upload</button>
        </form>

        <form method="POST" action="{{ route('notes.destroy', $note) }}">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>

        <p><a href="{{ route('notes.index') }}">Back to my notes</a></p>
    </main>
</body>
</html>
