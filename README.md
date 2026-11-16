# Secure web development

**Learn. Build. Ship.** Learn the common ways web apps are attacked and the small habits that stop them, then fix real vulnerabilities in a Laravel app, each with a test that proves the fix.

## Start here

Do the steps in order. Each folder matches one lesson and holds the finished code for it, so you can compare your work or start from the step before.

| Step | Folder | Lesson | Your workspace |
| --- | --- | --- | --- |
| 01 | [`step-01-web-vulnerabilities-explained`](step-01-web-vulnerabilities-explained) | **Common web vulnerabilities, explained** (on ewoibuilds.com: `/lessons/web-vulnerabilities-explained`) | `~/ewoi-learning/security-basics` |
| 02 | [`step-02-hash-passwords-properly`](step-02-hash-passwords-properly) | **Hash passwords properly** (on ewoibuilds.com: `/lessons/hash-passwords-properly`) | `~/ewoi-learning/laravel-app` |
| 03 | [`step-03-secure-a-laravel-app`](step-03-secure-a-laravel-app) | **Secure a Laravel app** (on ewoibuilds.com: `/lessons/secure-a-laravel-app`) | `~/ewoi-learning/secure-notes` |

Everything here runs only on your own computer. Only test apps you own, or practice labs made for learning; testing other systems without written permission is illegal in most countries.

## What you need

- A laptop or desktop (8 GB RAM recommended) and a stable internet connection
- Operating system: Windows 10 or 11, macOS or Linux
- Prerequisites: you can create a small Laravel app and run commands in a terminal
- Tools (search for each on its official site; download only from the official site and check the address):
  - PHP 8.3 or newer (8.4 used here), with the SQLite extension: search for "PHP official download"
  - Composer 2: search for "Composer official installer"
  - Node.js LTS (step 03 only): search for "Node.js official download"
  - Git: search for "Git official download"
  - A code editor

## Git setup (once)

1. Set your identity: `git config --global user.name "Your Name"` and
   `git config --global user.email "you@example.com"`.
2. To push your own copy, set up an SSH key (`ssh-keygen -t ed25519 -C "you@example.com"`, add the
   `.pub` key on GitHub under Settings, SSH and GPG keys, test with `ssh -T git@github.com`) or a
   fine-grained personal access token used as your password over HTTPS. Keep the private key and the
   token to yourself.
3. Create your own repository on GitHub by hand, then clone it by SSH
   (`git@github.com:USER/REPO.git`) or HTTPS (`https://github.com/USER/REPO.git`).
4. `.gitignore` already leaves out `.env`, `vendor/` and `node_modules/`. Commit `.env.example` with
   placeholders and keep `.env` on your machine.

## How to use this repo

```
git clone https://github.com/ewoibuilds/secure-web-development.git
cd secure-web-development
```

- **Compare your work:** after each lesson, open the matching step folder and compare it with the
  files in your workspace.
- **Start from the step before:** stuck? Copy the files from the previous step into your workspace
  and carry on from there.
- **Laravel steps (02 and 03)** hold only the files you create or change, at their real Laravel paths.
  Create the app as the lesson says, then compare or copy these files into it.

## Check it works

| Step | Run | You should see |
| --- | --- | --- |
| 01 | `php 01-sql-prepared-statements.php` (and the other two files) | `Result: no user found` for the injection text, escaped HTML, `rejected` for forms without the token |
| 02 | `php scripts/hash-demo.php` and `php artisan test --filter=PasswordHashingTest` | Two different hashes that both match, then 3 passed tests |
| 03 | `php artisan test` | Every test passes, including the 9 in `NoteSecurityTest` |

## Project structure

```
step-01-web-vulnerabilities-explained/   three plain PHP demos of safe patterns
step-02-hash-passwords-properly/         a password hashing script and a Laravel test
step-03-secure-a-laravel-app/            the notes app files after all seven fixes, with tests
```

## Troubleshooting

| Problem | Fix |
| --- | --- |
| `php: command not found` | Install PHP from the official site and open a new terminal |
| `could not find driver` | Enable the `pdo_sqlite` extension for your PHP (search for "PHP enable pdo_sqlite" for your system) |
| `laravel: command not found` | Run `composer global config bin-dir --absolute` and add that folder to your PATH |
| `Address already in use` with `php -S` | Another program uses port 8000: run `php -S localhost:8001` instead |
| Tests fail after copying step 03 files | Check you created the app with the Livewire starter kit, then run `php artisan migrate` and `php artisan test` again |

## Using AI on this repo

Know the concept first, then ask for small steps with the goal, stack, versions and constraints.
Check every answer against the lesson and the official docs, and keep secrets out of your prompts.

A good prompt:

> I am learning web security with Laravel 13 and PHP 8.4. Here is my policy and the test that fails: [paste them]. Explain in simple words why the test fails and give me a hint, not the full answer.

## Support Ewoi Builds

Lessons stay free. If this helped, star the repo, follow the organisation and share it.

Follow along: GitHub (github.com/ewoibuilds), TikTok (@ewoibuilds), Instagram (@ewoibuilds).

## Licence

MIT. See [LICENSE](LICENSE).
