<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid; 
use App\Models\Regional;

/**
 * Classe RegionaisSeeder responsável por popular o banco de dados com as regionais iniciais.
 */
class RegionaisSeeder extends Seeder
{
    /**
     * Executa os seeds no banco de dados.
     *
     * Este método verifica se as regionais já existem no banco de dados 
     * e as cria caso não estejam presentes. Cada regional possui um UUID único e um label.
     *
     * @return void
     */
    public function run()
    {
        // Lista de regionais a serem adicionadas, com seus respectivos UUIDs e labels
        $regionals = [
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Alto tietê'], // Regional 1
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Interior'],   // Regional 2
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'ES'],         // Regional 3
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP Interior'],// Regional 4
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP'],         // Regional 5
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP2'],        // Regional 6
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'MG'],         // Regional 7
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Nacional'],   // Regional 8
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP CAV'],     // Regional 9
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'RJ'],         // Regional 10
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP2'],        // Regional 11
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP1'],        // Regional 12
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'NE1'],        // Regional 13
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'NE2'],        // Regional 14
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SUL'],        // Regional 15
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Norte'],      // Regional 16
        ];

        // Itera sobre cada regional da lista
        foreach ($regionals as $regional) {
            // Verifica se uma regional com o mesmo label já existe no banco de dados
            if (!Regional::where('label', $regional['label'])->exists()) {
                // Cria a regional no banco de dados com UUID e label fornecidos
                Regional::create([
                    'uuid' => $regional['uuid'],   // Identificador único
                    'label' => $regional['label'], // Nome ou descrição da regional
                ]);
            }
        }
    }
}