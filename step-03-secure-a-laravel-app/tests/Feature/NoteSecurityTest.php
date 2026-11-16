<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NoteSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get('/notes')->assertRedirect(route('login'));
        $this->get('/notes/search?q=a')->assertRedirect(route('login'));
    }

    public function test_search_treats_input_as_text_and_only_shows_my_notes(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $me->notes()->create(['title' => "It's mine", 'body' => 'x']);
        $other->notes()->create(['title' => "It's secret", 'body' => 'x']);

        $this->actingAs($me)
            ->get('/notes/search?q='.urlencode("it's"))
            ->assertOk()
            ->assertExactJson(["It's mine"]);
    }

    public function test_note_titles_are_escaped_on_the_page(): void
    {
        $me = User::factory()->create();
        $note = $me->notes()->create(['title' => "<script>alert('xss')</script>", 'body' => 'x']);

        $this->actingAs($me)
            ->get("/notes/{$note->id}")
            ->assertOk()
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_a_user_cannot_view_or_delete_another_users_note(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $note = $other->notes()->create(['title' => 'Private', 'body' => 'x']);

        $this->actingAs($me)->get("/notes/{$note->id}")->assertForbidden();
        $this->actingAs($me)->delete("/notes/{$note->id}")->assertForbidden();
        $this->assertDatabaseHas('notes', ['id' => $note->id]);
    }

    public function test_only_title_and_body_can_be_filled(): void
    {
        $me = User::factory()->create();

        $this->actingAs($me)->post('/notes', [
            'title' => 'Shopping',
            'body' => 'Milk',
            'is_pinned' => 1,
        ])->assertRedirect('/notes');

        $this->assertDatabaseHas('notes', ['title' => 'Shopping', 'is_pinned' => false]);
    }

    public function test_invalid_notes_are_rejected(): void
    {
        $me = User::factory()->create();

        $this->actingAs($me)
            ->post('/notes', ['title' => str_repeat('a', 201), 'body' => ''])
            ->assertSessionHasErrors(['title', 'body']);

        $this->assertDatabaseCount('notes', 0);
    }

    public function test_a_cross_site_form_without_a_csrf_token_is_rejected(): void
    {
        $me = User::factory()->create();

        // Laravel skips the CSRF check while tests run, so switch it back on for this one request.
        $this->app['env'] = 'local';

        $this->actingAs($me)
            ->withHeader('Sec-Fetch-Site', 'cross-site')
            ->post('/notes', ['title' => 'Forged', 'body' => 'x'])
            ->assertStatus(419);

        $this->assertDatabaseCount('notes', 0);
    }

    public function test_uploads_are_checked_and_stored_privately(): void
    {
        Storage::fake('local');
        $me = User::factory()->create();
        $note = $me->notes()->create(['title' => 'Receipt', 'body' => 'x']);

        $this->actingAs($me)
            ->post("/notes/{$note->id}/attachment", [
                'attachment' => UploadedFile::fake()->create('shell.php', 1, 'application/x-php'),
            ])
            ->assertSessionHasErrors('attachment');

        $this->actingAs($me)
            ->post("/notes/{$note->id}/attachment", [
                'attachment' => UploadedFile::fake()->create('receipt.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect("/notes/{$note->id}");

        $path = $note->fresh()->attachment_path;
        $this->assertStringStartsWith('attachments/', $path);
        $this->assertStringNotContainsString('receipt', $path);
        Storage::disk('local')->assertExists($path);
    }

    public function test_note_creation_is_rate_limited(): void
    {
        $me = User::factory()->create();

        for ($i = 1; $i <= 10; $i++) {
            $this->actingAs($me)->post('/notes', ['title' => "Note $i", 'body' => 'x'])->assertRedirect('/notes');
        }

        $this->actingAs($me)->post('/notes', ['title' => 'One too many', 'body' => 'x'])->assertStatus(429);
    }
}
