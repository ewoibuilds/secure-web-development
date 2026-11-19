# Step 02: Hash passwords properly

**Lesson:** [Hash passwords properly](https://ewoibuilds.com/lessons/hash-passwords-properly)

**Your workspace:** `~/ewoi-learning/laravel-app` (the Laravel 13 app from "Your first Laravel route")

## What this step adds

- `scripts/hash-demo.php`: PHP's own `password_hash`, `password_verify` and `password_needs_rehash`, with no framework.
- `tests/Feature/PasswordHashingTest.php`: a Laravel test that proves the database stores a hash, never the password, and that the `hashed` cast on the `User` model does it automatically.

These are the only files you create. Create the app as the lesson says, then compare or copy these files into it.

## Files

```
laravel-app/
├── scripts/
│   └── hash-demo.php
└── tests/
    └── Feature/
        └── PasswordHashingTest.php
```

## How to run it

```
cd ~/ewoi-learning/laravel-app
php scripts/hash-demo.php
php artisan test --filter=PasswordHashingTest
```

## Expected output

The hashes are different every time you run it; that is the salt at work.

```
First hash:  $2y$12$e1AiBlJ3fRNFFYgVcXvb6uV1pLwMcrFsDhQ735DH46XveXzFRmVMe
Second hash: $2y$12$z5Uj00Bs5W0.82YZWS.qOO6YlZCvSDIIdN9DyrAYZGs7PmaY3Ft32
Same text? no
Right password matches the first: yes
Right password matches the second: yes
Wrong password matches: no
Old cost-10 hash needs an upgrade: yes
```

Then the test run ends with `Tests: 3 passed`.

## Check

- Both hashes start with `$2y$12$` (bcrypt, cost 12). On PHP older than 8.4 the script shows `$2y$10$`; Laravel still uses 12.
- All three tests pass. Tests use `BCRYPT_ROUNDS=4` from `phpunit.xml` to stay fast; the app itself uses 12.

## Commit

```
git add scripts/hash-demo.php tests/Feature/PasswordHashingTest.php
git commit -m "Add password hashing demo and tests"
```

## Next step

[Step 03: Secure a Laravel app](../step-03-secure-a-laravel-app)

<!-- ewoi:step -->
---

**Next:** [step-03-secure-a-laravel-app](../step-03-secure-a-laravel-app/) &nbsp;&middot;&nbsp; **Stuck?** [Ask with the "I am stuck" form](https://github.com/ewoibuilds/secure-web-development/issues/new/choose) &nbsp;&middot;&nbsp; **Helped?** Star [the repo](https://github.com/ewoibuilds/secure-web-development) and follow [Ewoi Builds](https://github.com/ewoibuilds).
<!-- /ewoi:step -->
