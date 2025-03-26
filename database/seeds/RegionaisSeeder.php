<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid; 
use App\Models\Regional;

class RegionaisSeeder extends Seeder
{
    public function run()
    {
        $regionals = [
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Alto tietê'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Interior'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'ES'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP Interior'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP2'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'MG'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Nacional'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP CAV'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'RJ'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP2'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SP1'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'NE1'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'NE2'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'SUL'],
            ['uuid' => Uuid::uuid4()->toString(), 'label' => 'Norte'],
        ];

        foreach ($regionals as $regional) {
            if (!Regional::where('label', $regional['label'])->exists()) {
                Regional::create([
                    'uuid' => $regional['uuid'],
                    'label' => $regional['label'],
                ]);
            }
        }
    }
}