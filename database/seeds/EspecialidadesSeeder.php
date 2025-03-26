<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use App\Models\Especialidade;

class EspecialidadesSeeder extends Seeder
{
    public function run()
    {
        $especialidades = [
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Alergologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Anestesiologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Angiologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Cardiologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Cirurgia Geral'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Cirurgia Plástica'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Clínica Geral'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Dermatologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Endocrinologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Gastroenterologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Geriatria'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Ginecologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Hematologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Imunologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Infectologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Medicina do Trabalho'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Medicina Esportiva'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Nefrologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Neurologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Nutrologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Oftalmologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Oncologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Ortopedia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Otorrinolaringologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Patologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Pediatria'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Psiquiatria'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Radiologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Reumatologia'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Urologia'],
        ];

        foreach ($especialidades as $especialidade) {
            if (!Especialidade::where('label', $especialidade['label'])->exists()) {
                Especialidade::create([
                    'uuid' => $especialidade['uuid'],
                    'label' => $especialidade['label'],
                ]);
            }
        }

    }
}