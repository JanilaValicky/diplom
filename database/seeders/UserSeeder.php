<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $password = Hash::make(config('seeder.users.password'));
        User::factory(1000)->create([
            'password' => $password,
        ]);
    }
}
