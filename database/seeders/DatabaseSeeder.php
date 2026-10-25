<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = config('admin.accounts', []);
        $emails = [];

        if (count($accounts) !== 3) {
            throw new RuntimeException('Exactly three admin accounts must be configured before seeding.');
        }

        foreach ($accounts as $account) {
            if (
                blank($account['name'] ?? null)
                || blank($account['email'] ?? null)
                || blank($account['password'] ?? null)
            ) {
                throw new RuntimeException(
                    'Set ADMIN_1_EMAIL/PASSWORD, ADMIN_2_EMAIL/PASSWORD, and ADMIN_3_EMAIL/PASSWORD before seeding.'
                );
            }

            if (! filter_var($account['email'], FILTER_VALIDATE_EMAIL) || in_array($account['email'], $emails, true)) {
                throw new RuntimeException('Each configured admin must have a unique, valid email address.');
            }

            if (app()->environment('production') && mb_strlen($account['password']) < 12) {
                throw new RuntimeException('Admin passwords must contain at least 12 characters in production.');
            }

            $existingUser = User::query()->where('email', $account['email'])->first();
            if ($existingUser && $existingUser->role !== 'admin') {
                throw new RuntimeException('A configured admin email already belongs to a non-admin user.');
            }

            $emails[] = $account['email'];
        }

        DB::transaction(function () use ($accounts): void {
            foreach ($accounts as $account) {
                User::firstOrCreate(
                    ['email' => $account['email']],
                    [
                        'name' => $account['name'],
                        'password' => Hash::make($account['password']),
                        'role' => 'admin',
                    ]
                );
            }
        });
    }
}
