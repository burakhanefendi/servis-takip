<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CariGroup;

class CariGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            ['name' => 'Bireysel', 'description' => 'Bireysel müşteriler'],
            ['name' => 'Kurumsal', 'description' => 'Kurumsal firmalar'],
            ['name' => 'Filtre Değişimi', 'description' => 'Filtre değişimi müşterileri'],
            ['name' => 'Satılan Cihaz', 'description' => 'Cihaz satın alan müşteriler'],
        ];

        foreach ($groups as $group) {
            CariGroup::firstOrCreate(
                ['name' => $group['name']],
                ['description' => $group['description']]
            );
        }
    }
}
