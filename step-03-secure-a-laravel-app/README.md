# Step 03: Secure a Laravel app

**Lesson:** **Secure a Laravel app: a hands-on walkthrough** (on ewoibuilds.com: `/lessons/secure-a-laravel-app`)

**Your workspace:** `~/ewoi-learning/secure-notes`

**Laravel version:** 13, with the Livewire starter kit and PHPUnit.

## What this step adds

A small notes app after all seven fixes from the walkthrough, plus one test per fix. The walkthrough shows the unsafe version of each file first; this folder holds only the finished, safe files.

| Fix | Where | Proved by |
| --- | --- | --- |
| 1. SQL injection in search | `NoteController::search` uses the query builder, scoped to the user | `test_search_treats_input_as_text_and_only_shows_my_notes` |
| 2. XSS on the note page | `{{ }}` in `notes/show.blade.php` | `test_note_titles_are_escaped_on_the_page` |
| 3. Broken access control | `NotePolicy` and `Gate::authorize`; `auth` middleware on every notes route | `test_guests_are_sent_to_the_login_page`, `test_a_user_cannot_view_or_delete_another_users_note` |
| 4. Mass assignment and validation | `#[Fillable(['title', 'body'])]` and `$request->validate` | `test_only_title_and_body_can_be_filled`, `test_invalid_notes_are_rejected` |
| 5. CSRF | `@csrf` in every form, no exceptions in `bootstrap/app.php` | `test_a_cross_site_form_without_a_csrf_token_is_rejected` |
| 6. File uploads | Type and size checked, random name, private storage, download behind the policy | `test_uploads_are_checked_and_stored_privately` |
| 7. Limits | `throttle:10,1` on saving notes | `test_note_creation_is_rate_limited` |

## Files

These are the only files you create or change. Create the app as the walkthrough says, then compare or copy these files into it.

```
secure-notes/
├── app/
│   ├── Http/Controllers/NoteController.php
│   ├── Models/Note.php
│   ├── Models/User.php                         (adds the notes() relationship)
│   └── Policies/NotePolicy.php
├── database/migrations/2026_01_01_000000_create_notes_table.php
├── resources/views/notes/
│   ├── index.blade.php
│   └── show.blade.php
├── routes/web.php                              (adds the notes routes)
└── tests/Feature/NoteSecurityTest.php
```

Your migration has the date and time you created it in its name; keep your own name and compare the content. `User.php` and `routes/web.php` are the starter kit's files with the additions marked above; if your kit's version differs, copy only the additions.

## How to run it

Create the app (needs PHP 8.3 or newer, Composer, Node.js and the Laravel installer):

```
cd ~/ewoi-learning
laravel new secure-notes --livewire --phpunit --database=sqlite --npm --no-boost --no-interaction
cd secure-notes
```

Copy the files from this folder into the matching paths, then:

```
php artisan migrate
php artisan test
composer run dev
```

Open `http://localhost:8000/register`, create an account, then go to `http://localhost:8000/notes`.

## Expected output

`php artisan test` ends with every test passing, including:

```
PASS  Tests\Feature\NoteSecurityTest
✓ guests are sent to the login page
✓ search treats input as text and only shows my notes
✓ note titles are escaped on the page
✓ a user cannot view or delete another users note
✓ only title and body can be filled
✓ invalid notes are rejected
✓ a cross site form without a csrf token is rejected
✓ uploads are checked and stored privately
✓ note creation is rate limited
```

## Check

- Signed in as one user, opening another user's note address gives `403 Forbidden`.
- A note titled with a `<script>` tag shows the tag as text.
- Searching for `it's` returns results instead of an error.

## Commit

The walkthrough commits after each fix. The last one is:

```
git add routes/web.php tests/Feature/NoteSecurityTest.php
git commit -m "Fix: limit how often notes can be saved"
```

## Next step

You have finished the path. Run the same checks on one of your own Laravel projects, one fix and one test at a time.
