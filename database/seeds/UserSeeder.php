<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Executa os seeds no banco de dados.
     *
     * Este método cria um usuário administrador padrão, se ele ainda não existir no banco de dados.
     *
     * @return void
     */
    public function run()
    {
        // Verifica se um usuário com o email 'admin@mail.com' já está cadastrado
        if (!User::where('email', 'admin@mail.com')->exists()) {
            // Cria um novo usuário administrador com credenciais padrão
            User::create([
                'name' => 'Admin User',           // Nome do usuário administrador
                'email' => 'admin@mail.com',      // Email do administrador
                'password' => Hash::make('admin123'), // Senha padrão (hash para segurança)
            ]);
        } else {
            // Exibe uma mensagem informando que o usuário já existe
            echo "O usuário admin (default) já existe e não foi recriado.\n";
        }
    }
}