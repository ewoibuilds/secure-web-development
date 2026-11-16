<?php

declare(strict_types=1);

/*
 * SQL injection: the safe way to put user input into a query.
 *
 * The unsafe pattern (never write this):
 *     "SELECT name FROM users WHERE email = '" . $email . "'"
 * Gluing the input into the SQL text lets the input change the query itself.
 *
 * The safe pattern: a prepared statement with a placeholder (?). The SQL and
 * the input travel separately, so the input is always treated as plain text.
 */

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('CREATE TABLE users (id INTEGER PRIMARY KEY, email TEXT NOT NULL, name TEXT NOT NULL)');
$pdo->exec("INSERT INTO users (email, name) VALUES ('amina@example.com', 'Amina'), ('brian@example.com', 'Brian')");

function findUserName(PDO $pdo, string $email): ?string
{
    $statement = $pdo->prepare('SELECT name FROM users WHERE email = ?');
    $statement->execute([$email]);
    $name = $statement->fetchColumn();

    return $name === false ? null : $name;
}

if (PHP_SAPI === 'cli') {
    echo "Unsafe pattern: gluing input into the SQL text.\n";
    echo "Safe pattern: a prepared statement with a ? placeholder.\n\n";

    foreach (['amina@example.com', "' OR '1'='1", "it's@example.com"] as $input) {
        $name = findUserName($pdo, $input);
        echo 'Search for: '.$input."\n";
        echo 'Result: '.($name ?? 'no user found')."\n\n";
    }

    exit(0);
}

$input = (string) ($_GET['email'] ?? '');
$name = $input === '' ? null : findUserName($pdo, $input);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prepared statements</title>
</head>
<body>
    <h1>Find a user by email</h1>
    <form method="GET">
        <label for="email">Email</label>
        <input id="email" name="email" value="<?= htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
        <button type="submit">Search</button>
    </form>
    <?php if ($input !== ''): ?>
        <p>Result: <?= htmlspecialchars($name ?? 'no user found', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php endif; ?>
    <p>Try amina@example.com, then try typing a single quote. The query never breaks, because the input is sent separately from the SQL.</p>
</body>
</html>
