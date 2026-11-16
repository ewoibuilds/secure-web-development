<?php

declare(strict_types=1);

/*
 * Cross-site scripting (XSS): the safe way to print user input in a page.
 *
 * The unsafe pattern (never write this):
 *     echo $comment;
 * Printing input raw lets a <script> tag inside it run in every visitor's browser.
 *
 * The safe pattern: escape on output. htmlspecialchars turns < > " ' & into
 * harmless codes, so the browser shows the text instead of running it.
 * (Laravel's Blade does this for you with {{ $comment }}.)
 */

function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if (PHP_SAPI === 'cli') {
    $comment = "<script>alert('hi')</script> Nice post!";

    echo "Unsafe pattern: printing input raw.\n";
    echo "Safe pattern: escaping it with htmlspecialchars.\n\n";
    echo 'Comment typed: '.$comment."\n";
    echo 'Sent to the browser: '.e($comment)."\n";

    exit(0);
}

$comment = (string) ($_GET['comment'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Escape output</title>
</head>
<body>
    <h1>Leave a comment</h1>
    <form method="GET">
        <label for="comment">Comment</label>
        <input id="comment" name="comment" value="<?= e($comment) ?>">
        <button type="submit">Show it</button>
    </form>
    <?php if ($comment !== ''): ?>
        <p>Your comment: <?= e($comment) ?></p>
    <?php endif; ?>
    <p>Type a comment that contains a &lt;script&gt; tag. It appears as text and never runs.</p>
</body>
</html>
