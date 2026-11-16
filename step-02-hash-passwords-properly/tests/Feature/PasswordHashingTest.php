<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordHashingTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_database_stores_a_hash_not_the_password(): void
    {
        $user = User::factory()->create(['password' => 'correct-horse-battery-staple']);

        $stored = DB::table('users')->where('id', $user->id)->value('password');

        $this->assertNotSame('correct-horse-battery-staple', $stored);
        $this->assertStringStartsWith('$2y$', $stored);
        $this->assertTrue(Hash::check('correct-horse-battery-staple', $stored));
        $this->assertFalse(Hash::check('wrong-guess', $stored));
    }

    public function test_the_same_password_gives_a_different_hash_each_time(): void
    {
        $first = Hash::make('kenya2026');
        $second = Hash::make('kenya2026');

        $this->assertNotSame($first, $second);
        $this->assertTrue(Hash::check('kenya2026', $first));
        $this->assertTrue(Hash::check('kenya2026', $second));
    }

    public function test_the_hashed_cast_hashes_a_plain_password_on_save(): void
    {
        $user = User::factory()->create();

        $user->password = 'a-new-long-passphrase';
        $user->save();

        $stored = DB::table('users')->where('id', $user->id)->value('password');

        $this->assertNotSame('a-new-long-passphrase', $stored);
        $this->assertTrue(Hash::check('a-new-long-passphrase', $stored));
    }
}
