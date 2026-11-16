<?php

declare(strict_types=1);

$password = 'kenya2026';

$first = password_hash($password, PASSWORD_DEFAULT);
$second = password_hash($password, PASSWORD_DEFAULT);

echo 'First hash:  '.$first."\n";
echo 'Second hash: '.$second."\n";
echo 'Same text? '.($first === $second ? 'yes' : 'no')."\n";
echo 'Right password matches the first: '.(password_verify($password, $first) ? 'yes' : 'no')."\n";
echo 'Right password matches the second: '.(password_verify($password, $second) ? 'yes' : 'no')."\n";
echo 'Wrong password matches: '.(password_verify('kenya2025', $first) ? 'yes' : 'no')."\n";

$old = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
echo 'Old cost-10 hash needs an upgrade: '.(password_needs_rehash($old, PASSWORD_BCRYPT, ['cost' => 12]) ? 'yes' : 'no')."\n";
