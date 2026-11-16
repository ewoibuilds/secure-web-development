<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        return view('notes.index', [
            'notes' => $request->user()->notes()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $request->user()->notes()->create($data);

        return redirect()->route('notes.index');
    }

    public function search(Request $request): JsonResponse
    {
        $q = $request->string('q')->limit(100);

        return response()->json(
            $request->user()->notes()->where('title', 'like', '%'.$q.'%')->pluck('title')
        );
    }

    public function show(Note $note): View
    {
        Gate::authorize('view', $note);

        return view('notes.show', compact('note'));
    }

    public function destroy(Note $note): RedirectResponse
    {
        Gate::authorize('delete', $note);

        $note->delete();

        return redirect()->route('notes.index');
    }

    public function attach(Request $request, Note $note): RedirectResponse
    {
        Gate::authorize('update', $note);

        $request->validate([
            'attachment' => ['required', 'file', 'mimes:pdf,png,jpg', 'max:2048'],
        ]);

        $note->attachment_path = $request->file('attachment')->store('attachments');
        $note->save();

        return redirect()->route('notes.show', $note);
    }

    public function download(Note $note): StreamedResponse
    {
        Gate::authorize('view', $note);

        abort_if($note->attachment_path === null, 404);

        return Storage::download($note->attachment_path);
    }
}
