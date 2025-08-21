<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Table;

class RestaurantTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Menambahkan meja sesuai dengan kapasitas yang ada:
     * - 4 meja untuk kapasitas 2 orang
     * - 4 meja untuk kapasitas 4 orang  
     * - 4 meja untuk kapasitas 6 orang
     * - 3 meja untuk kapasitas 8 orang
     * - 3 meja untuk kapasitas 10 orang
     * - 2 meja untuk kapasitas 15 orang
     * - 2 meja untuk kapasitas 20 orang
     * - 2 ruangan VIP untuk kapasitas 30 orang
     */
    public function run(): void
    {
        // Get the highest numeric table number that currently exists
        $existingTableNumbers = Table::all()->pluck('table_number')->filter(function($number) {
            return is_numeric($number);
        })->map(function($number) {
            return (int) $number;
        });
        
        $lastTableNumber = $existingTableNumbers->max() ?? 0;
        $currentNumber = $lastTableNumber;
        
        $newTables = [];
        
        // 4 meja kapasitas 2 orang (tapi kita sudah punya 3, jadi tambah 1 lagi)
        $existing2Capacity = Table::where('capacity', 2)->count();
        $needed2Capacity = max(0, 4 - $existing2Capacity);
        
        for ($i = 1; $i <= $needed2Capacity; $i++) {
            $currentNumber++;
            $newTables[] = [
                'table_number' => (string) $currentNumber,
                'capacity' => 2,
                'status' => 'available',
                'description' => 'Meja romantis untuk 2 orang - Area ' . chr(64 + $i),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // 4 meja kapasitas 4 orang (kita sudah punya 4, jadi tidak perlu tambahan)
        $existing4Capacity = Table::where('capacity', 4)->count();
        $needed4Capacity = max(0, 4 - $existing4Capacity);
        
        for ($i = 1; $i <= $needed4Capacity; $i++) {
            $currentNumber++;
            $newTables[] = [
                'table_number' => (string) $currentNumber,
                'capacity' => 4,
                'status' => 'available',
                'description' => 'Meja keluarga untuk 4 orang - Area ' . chr(64 + $i),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // 4 meja kapasitas 6 orang (kita sudah punya 2, jadi tambah 2 lagi)
        $existing6Capacity = Table::where('capacity', 6)->count();
        $needed6Capacity = max(0, 4 - $existing6Capacity);
        
        for ($i = 1; $i <= $needed6Capacity; $i++) {
            $currentNumber++;
            $newTables[] = [
                'table_number' => (string) $currentNumber,
                'capacity' => 6,
                'status' => 'available',
                'description' => 'Meja besar untuk 6 orang - Area ' . chr(64 + $i),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // 3 meja kapasitas 8 orang (kita sudah punya 1, jadi tambah 2 lagi)
        $existing8Capacity = Table::where('capacity', 8)->count();
        $needed8Capacity = max(0, 3 - $existing8Capacity);
        
        for ($i = 1; $i <= $needed8Capacity; $i++) {
            $currentNumber++;
            $newTables[] = [
                'table_number' => (string) $currentNumber,
                'capacity' => 8,
                'status' => 'available',
                'description' => 'Meja grup untuk 8 orang - Area Premium ' . chr(64 + $i),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // 3 meja kapasitas 10 orang (kita belum punya, jadi tambah 3)
        $existing10Capacity = Table::where('capacity', 10)->count();
        $needed10Capacity = max(0, 3 - $existing10Capacity);
        
        for ($i = 1; $i <= $needed10Capacity; $i++) {
            $currentNumber++;
            $newTables[] = [
                'table_number' => (string) $currentNumber,
                'capacity' => 10,
                'status' => 'available',
                'description' => 'Meja gathering untuk 10 orang - Area Premium ' . chr(64 + $i),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // 2 meja kapasitas 15 orang (kita belum punya, jadi tambah 2)
        $existing15Capacity = Table::where('capacity', 15)->count();
        $needed15Capacity = max(0, 2 - $existing15Capacity);
        
        for ($i = 1; $i <= $needed15Capacity; $i++) {
            $currentNumber++;
            $newTables[] = [
                'table_number' => (string) $currentNumber,
                'capacity' => 15,
                'status' => 'available',
                'description' => 'Meja acara untuk 15 orang - Area Exclusive ' . chr(64 + $i),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // 2 meja kapasitas 20 orang (kita belum punya, jadi tambah 2)
        $existing20Capacity = Table::where('capacity', 20)->count();
        $needed20Capacity = max(0, 2 - $existing20Capacity);
        
        for ($i = 1; $i <= $needed20Capacity; $i++) {
            $currentNumber++;
            $newTables[] = [
                'table_number' => (string) $currentNumber,
                'capacity' => 20,
                'status' => 'available',
                'description' => 'Meja konferensi untuk 20 orang - Area Exclusive ' . chr(64 + $i),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // 2 ruangan VIP kapasitas 30 orang (kita belum punya, jadi tambah 2)
        $existing30Capacity = Table::where('capacity', 30)->count();
        $needed30Capacity = max(0, 2 - $existing30Capacity);
        
        for ($i = 1; $i <= $needed30Capacity; $i++) {
            $newTables[] = [
                'table_number' => 'VIP' . sprintf('%02d', $i),
                'capacity' => 30,
                'status' => 'available',
                'description' => 'Ruangan VIP untuk 30 orang - Private Dining Room ' . $i,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // Only insert if there are new tables to add
        if (count($newTables) > 0) {
            Table::insert($newTables);
            $this->command->info('Successfully added ' . count($newTables) . ' new tables to the restaurant.');
        } else {
            $this->command->info('All required tables already exist.');
        }
        
        // Show current distribution
        $this->command->info('Current table distribution:');
        $this->command->info('- ' . Table::where('capacity', 2)->count() . ' tables for 2 people (target: 4)');
        $this->command->info('- ' . Table::where('capacity', 4)->count() . ' tables for 4 people (target: 4)');
        $this->command->info('- ' . Table::where('capacity', 6)->count() . ' tables for 6 people (target: 4)');
        $this->command->info('- ' . Table::where('capacity', 8)->count() . ' tables for 8 people (target: 3)');
        $this->command->info('- ' . Table::where('capacity', 10)->count() . ' tables for 10 people (target: 3)');
        $this->command->info('- ' . Table::where('capacity', 15)->count() . ' tables for 15 people (target: 2)');
        $this->command->info('- ' . Table::where('capacity', 20)->count() . ' tables for 20 people (target: 2)');
        $this->command->info('- ' . Table::where('capacity', 30)->count() . ' VIP rooms for 30 people (target: 2)');
    }
}
