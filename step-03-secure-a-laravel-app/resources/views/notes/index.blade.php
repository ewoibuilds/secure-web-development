<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My notes</title>
</head>
<body>
    <main>
        <h1>My notes</h1>

        <form method="POST" action="{{ route('notes.store') }}">
            @csrf
            <p>
                <label for="title">Title</label>
                <input id="title" name="title" maxlength="200" required value="{{ old('title') }}">
                @error('title') <span>{{ $message }}</span> @enderror
            </p>
            <p>
                <label for="body">Note</label>
                <textarea id="body" name="body" required>{{ old('body') }}</textarea>
                @error('body') <span>{{ $message }}</span> @enderror
            </p>
            <button type="submit">Save note</button>
        </form>

        <form method="GET" action="{{ route('notes.search') }}">
            <label for="q">Search titles</label>
            <input id="q" name="q" maxlength="100">
            <button type="submit">Search</button>
        </form>

        <ul>
            @foreach ($notes as $note)
                <li><a href="{{ route('notes.show', $note) }}">{{ $note->title }}</a></li>
            @endforeach
        </ul>
    </main>
</body>
</html>
