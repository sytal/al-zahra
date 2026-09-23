<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // A random password in production (this seeder is "essential" —
        // it runs there too, per docs/CLAUDE.md Section 22B); a fixed,
        // memorable one in local/staging so devs can log in the same way
        // after every migrate:fresh --seed.
        $password = app()->isProduction() ? str()->random(24) : 'password';

        $director = User::firstOrCreate(
            ['email' => 'director@alzahra.institute'],
            [
                'name' => 'Dr. Al Zahra Director',
                'password' => bcrypt($password),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $director->assignRole('director');

        $admin = User::firstOrCreate(
            ['email' => 'admin@alzahra.institute'],
            [
                'name' => 'Al Zahra Admin',
                'password' => bcrypt($password),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // Editor and student are demo/test-only accounts (not real staff
        // or users) — only seed them outside production, same as the
        // rest of the demo content seeders.
        if (! app()->isProduction()) {
            $editor = User::firstOrCreate(
                ['email' => 'editor@alzahra.institute'],
                [
                    'name' => 'Al Zahra Editor',
                    'password' => bcrypt($password),
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );
            $editor->assignRole('editor');

            $student = User::firstOrCreate(
                ['email' => 'student@alzahra.institute'],
                [
                    'name' => 'Al Zahra Student',
                    'password' => bcrypt($password),
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );
            $student->assignRole('student');
        }
    }
}
