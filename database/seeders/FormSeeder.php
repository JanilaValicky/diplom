<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            Form::factory(rand(1, 4))
                ->create([
                    'user_id' => $user->id,
                    'updated_by' => $user->id,
                ]);
        }
    }
}
