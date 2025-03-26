<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verifica se o usuário já existe pelo email
        if (!User::where('email', 'admin@mail.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@mail.com',
                'password' => Hash::make('admin123'),
            ]);
        } else {
            echo "O usuário admin (default) já existe e não foi recriado.\n";
        }
    }
}