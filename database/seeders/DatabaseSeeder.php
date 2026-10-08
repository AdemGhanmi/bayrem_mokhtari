<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin login. Set ADMIN_EMAIL / ADMIN_PASSWORD in .env BEFORE seeding in production.
        $email = env('ADMIN_EMAIL', 'admin@bayrem.tn');
        $user = User::firstOrNew(['email' => $email]);
        if (! $user->exists) {
            $user->name = 'Bayrem Admin';
            $user->password = Hash::make(env('ADMIN_PASSWORD', 'ChangeMe123!'));
        }
        $user->is_admin = true;
        $user->save();

        $this->call(ContentSeeder::class);
    }
}
