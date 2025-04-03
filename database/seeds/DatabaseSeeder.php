<?php

use Illuminate\Database\Seeder;

/**
 * Classe DatabaseSeeder responsável por executar todos os seeds necessários para popular o banco de dados.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Executa os seeds no banco de dados.
     *
     * Este método chama outros seeders para preencher o banco de dados com as informações iniciais necessárias.
     * Ele organiza a ordem de execução dos seeders para garantir que as dependências sejam respeitadas.
     *
     * @return void
     */
    public function run()
    {
        // Executa o seeder de usuários, criando usuários iniciais como o admin padrão
        $this->call(UserSeeder::class);

        // Executa o seeder de regionais, populando o banco de dados com as regionais pré-definidas
        $this->call(RegionaisSeeder::class);

        // Executa o seeder de especialidades, adicionando as especialidades médicas ao banco de dados
        $this->call(EspecialidadesSeeder::class);
    }
}