<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use App\Models\Especialidade;

/**
 * Classe EspecialidadesSeeder responsável por popular o banco de dados com especialidades iniciais.
 */
class EspecialidadesSeeder extends Seeder
{
    /**
     * Executa os seeds no banco de dados.
     *
     * Este método verifica se cada especialidade na lista já existe no banco de dados
     * e, caso contrário, cria a especialidade com UUID e label fornecidos.
     *
     * @return void
     */
    public function run()
    {
        // Lista de especialidades a serem inseridas no banco de dados
        $especialidades = [
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Alergologia'],         // Especialidade 1
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Anestesiologia'],     // Especialidade 2
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Angiologia'],         // Especialidade 3
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Cardiologia'],        // Especialidade 4
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Cirurgia Geral'],     // Especialidade 5
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Cirurgia Plástica'],  // Especialidade 6
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Clínica Geral'],      // Especialidade 7
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Dermatologia'],       // Especialidade 8
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Endocrinologia'],     // Especialidade 9
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Gastroenterologia'],  // Especialidade 10
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Geriatria'],          // Especialidade 11
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Ginecologia'],        // Especialidade 12
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Hematologia'],        // Especialidade 13
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Imunologia'],         // Especialidade 14
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Infectologia'],       // Especialidade 15
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Medicina do Trabalho'],// Especialidade 16
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Medicina Esportiva'], // Especialidade 17
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Nefrologia'],         // Especialidade 18
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Neurologia'],         // Especialidade 19
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Nutrologia'],         // Especialidade 20
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Oftalmologia'],       // Especialidade 21
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Oncologia'],          // Especialidade 22
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Ortopedia'],          // Especialidade 23
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Otorrinolaringologia'],// Especialidade 24
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Patologia'],          // Especialidade 25
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Pediatria'],          // Especialidade 26
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Psiquiatria'],        // Especialidade 27
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Radiologia'],         // Especialidade 28
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Reumatologia'],       // Especialidade 29
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Urologia'],           // Especialidade 30
        ];

        // Itera sobre cada especialidade na lista
        foreach ($especialidades as $especialidade) {
            // Verifica se uma especialidade com o mesmo label já existe no banco de dados
            if (!Especialidade::where('label', $especialidade['label'])->exists()) {
                // Cria a especialidade no banco de dados com UUID e label fornecidos
                Especialidade::create([
                    'uuid' => $especialidade['uuid'],   // Identificador único
                    'label' => $especialidade['label'], // Nome ou descrição da especialidade
                ]);
            }
        }
    }
}