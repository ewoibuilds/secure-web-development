<?php

declare(strict_types=1);

/*
 * Cross-site request forgery (CSRF): the safe way to accept a form.
 *
 * The unsafe pattern (never write this):
 *     if ($_SERVER['REQUEST_METHOD'] === 'POST') { deleteAllNotes(); }
 * Any other site can make a signed-in visitor's browser send that POST.
 *
 * The safe pattern: put a secret, random token in the session and in every
 * form, and accept a POST only when the two match. Another site cannot read
 * the token, so it cannot build a form that passes. (Laravel does this for you
 * with @csrf.)
 */

function csrfToken(array &$session): string
{
    if (! isset($session['csrf_token'])) {
        $session['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $session['csrf_token'];
}

function csrfIsValid(array $session, ?string $submitted): bool
{
    return isset($session['csrf_token'])
        && is_string($submitted)
        && hash_equals($session['csrf_token'], $submitted);
}

if (PHP_SAPI === 'cli') {
    $session = [];
    $token = csrfToken($session);

    echo "Unsafe pattern: accepting any POST.\n";
    echo "Safe pattern: accepting a POST only with the session's token.\n\n";
    echo 'Form with the right token: '.(csrfIsValid($session, $token) ? 'accepted' : 'rejected')."\n";
    echo 'Form with no token: '.(csrfIsValid($session, null) ? 'accepted' : 'rejected')."\n";
    echo 'Form with a guessed token: '.(csrfIsValid($session, str_repeat('0', 64)) ? 'accepted' : 'rejected')."\n";

    exit(0);
}

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (! csrfIsValid($_SESSION, $_POST['csrf_token'] ?? null)) {
        http_response_code(419);
        $message = 'Rejected: the form had no valid token.';
    } else {
        $message = 'Accepted: the token matched.';
    }
}

$token = csrfToken($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSRF token</title>
</head>
<body>
    <h1>Delete my notes</h1>
    <?php if ($message !== ''): ?>
        <p><?= htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
        <button type="submit">Send with the token</button>
    </form>
    <form method="POST">
        <button type="submit">Send without the token</button>
    </form>
</body>
</html>
