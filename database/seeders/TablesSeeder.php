<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Table;

class TablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            [
                'table_number' => '01',
                'capacity' => 2,
                'status' => 'available',
                'description' => 'Meja kecil dekat jendela, cocok untuk pasangan'
            ],
            [
                'table_number' => '02',
                'capacity' => 2,
                'status' => 'available',
                'description' => 'Meja romantis di area tenang'
            ],
            [
                'table_number' => '03',
                'capacity' => 4,
                'status' => 'available',
                'description' => 'Meja keluarga di area tengah'
            ],
            [
                'table_number' => '04',
                'capacity' => 4,
                'status' => 'available',
                'description' => 'Meja persegi dekat dapur'
            ],
            [
                'table_number' => '05',
                'capacity' => 6,
                'status' => 'available',
                'description' => 'Meja besar untuk gathering keluarga'
            ],
            [
                'table_number' => '06',
                'capacity' => 6,
                'status' => 'available',
                'description' => 'Meja bulat di area VIP'
            ],
            [
                'table_number' => '07',
                'capacity' => 8,
                'status' => 'available',
                'description' => 'Meja besar untuk acara khusus'
            ],
            [
                'table_number' => '08',
                'capacity' => 4,
                'status' => 'occupied',
                'description' => 'Meja outdoor di teras'
            ],
            [
                'table_number' => '09',
                'capacity' => 2,
                'status' => 'reserved',
                'description' => 'Meja premium dengan view terbaik'
            ],
            [
                'table_number' => '10',
                'capacity' => 10,
                'status' => 'available',
                'description' => 'Meja besar untuk rombongan atau meeting'
            ]
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}
