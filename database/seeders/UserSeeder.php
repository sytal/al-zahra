<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $director = User::firstOrCreate(
            ['email' => 'director@alzahra.institute'],
            [
                'name' => 'Dr. Al Zahra Director',
                'password' => bcrypt(str()->random(24)),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $director->assignRole('director');

        $admin = User::firstOrCreate(
            ['email' => 'admin@alzahra.institute'],
            [
                'name' => 'Al Zahra Admin',
                'password' => bcrypt(str()->random(24)),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');
    }
}
