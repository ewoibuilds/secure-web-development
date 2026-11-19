# Step 01: Common web vulnerabilities, explained

**Lesson:** [Common web vulnerabilities, explained](https://ewoibuilds.com/lessons/web-vulnerabilities-explained)

**Your workspace:** `~/ewoi-learning/security-basics`

## What this step adds

Three tiny PHP files, one per classic risk. Each describes the unsafe pattern at the top and runs only the safe fix, so nothing here attacks anything.

| File | Risk | The safe pattern |
| --- | --- | --- |
| `01-sql-prepared-statements.php` | SQL injection | A prepared statement with a `?` placeholder instead of gluing input into SQL |
| `02-escape-output.php` | Cross-site scripting (XSS) | `htmlspecialchars` on output instead of printing input raw |
| `03-csrf-token.php` | Cross-site request forgery (CSRF) | A secret session token checked with `hash_equals` before accepting a form |

## Files

```
security-basics/
├── 01-sql-prepared-statements.php
├── 02-escape-output.php
└── 03-csrf-token.php
```

## How to run it

```
cd ~/ewoi-learning/security-basics
php 01-sql-prepared-statements.php
php 02-escape-output.php
php 03-csrf-token.php
```

To try them in the browser, start PHP's built-in server and open `http://localhost:8000/01-sql-prepared-statements.php` (then the other two). Press `Ctrl+C` to stop it.

```
php -S localhost:8000
```

## Expected output

```
Search for: amina@example.com
Result: Amina

Search for: ' OR '1'='1
Result: no user found

Search for: it's@example.com
Result: no user found
```

```
Comment typed: <script>alert('hi')</script> Nice post!
Sent to the browser: &lt;script&gt;alert(&#039;hi&#039;)&lt;/script&gt; Nice post!
```

```
Form with the right token: accepted
Form with no token: rejected
Form with a guessed token: rejected
```

In the browser, the CSRF page accepts the button that sends the token and answers the other one with status 419.

## Check

- The injection text finds nobody, and a single quote never causes an error.
- The `<script>` tag appears as text and never runs.
- Only the form with the token is accepted.

## Commit

```
git add 01-sql-prepared-statements.php 02-escape-output.php 03-csrf-token.php
git commit -m "Add safe demos for SQL injection, XSS and CSRF"
```

## Next step

[Step 02: Hash passwords properly](../step-02-hash-passwords-properly)

<!-- ewoi:step -->
---

**Next:** [step-02-hash-passwords-properly](../step-02-hash-passwords-properly/) &nbsp;&middot;&nbsp; **Stuck?** [Ask with the "I am stuck" form](https://github.com/ewoibuilds/secure-web-development/issues/new/choose) &nbsp;&middot;&nbsp; **Helped?** Star [the repo](https://github.com/ewoibuilds/secure-web-development) and follow [Ewoi Builds](https://github.com/ewoibuilds).
<!-- /ewoi:step -->
