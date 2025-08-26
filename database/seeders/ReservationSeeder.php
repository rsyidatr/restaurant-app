<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = Table::all();
        
        if ($tables->isEmpty()) {
            $this->command->info('No tables found. Please run TablesSeeder first.');
            return;
        }

        $reservations = [
            // Today's reservations
            [
                'customer_name' => 'John Doe',
                'customer_email' => 'john@example.com',
                'customer_phone' => '081234567890',
                'reservation_time' => Carbon::today()->setTime(12, 0, 0),
                'party_size' => 4,
                'status' => 'pending',
                'special_requests' => 'Table near window'
            ],
            [
                'customer_name' => 'Jane Smith',
                'customer_email' => 'jane@example.com',
                'customer_phone' => '081234567891',
                'reservation_time' => Carbon::today()->setTime(18, 30, 0),
                'party_size' => 2,
                'status' => 'confirmed',
                'special_requests' => null
            ],
            [
                'customer_name' => 'Bob Wilson',
                'customer_email' => 'bob@example.com',
                'customer_phone' => '081234567892',
                'reservation_time' => Carbon::today()->setTime(19, 0, 0),
                'party_size' => 6,
                'status' => 'pending',
                'special_requests' => 'Birthday celebration'
            ],
            [
                'customer_name' => 'Alice Brown',
                'customer_email' => 'alice@example.com',
                'customer_phone' => '081234567893',
                'reservation_time' => Carbon::today()->setTime(20, 0, 0),
                'party_size' => 8,
                'status' => 'confirmed',
                'special_requests' => 'Business dinner'
            ],
            // Tomorrow's reservations
            [
                'customer_name' => 'Charlie Davis',
                'customer_email' => 'charlie@example.com',
                'customer_phone' => '081234567894',
                'reservation_time' => Carbon::tomorrow()->setTime(12, 30, 0),
                'party_size' => 3,
                'status' => 'pending',
                'special_requests' => null
            ],
            [
                'customer_name' => 'Diana Evans',
                'customer_email' => 'diana@example.com',
                'customer_phone' => '081234567895',
                'reservation_time' => Carbon::tomorrow()->setTime(19, 30, 0),
                'party_size' => 5,
                'status' => 'confirmed',
                'special_requests' => 'Vegetarian menu'
            ],
            // Yesterday's reservations (completed)
            [
                'customer_name' => 'Frank Green',
                'customer_email' => 'frank@example.com',
                'customer_phone' => '081234567896',
                'reservation_time' => Carbon::yesterday()->setTime(18, 0, 0),
                'party_size' => 4,
                'status' => 'completed',
                'special_requests' => null
            ],
            [
                'customer_name' => 'Grace Hall',
                'customer_email' => 'grace@example.com',
                'customer_phone' => '081234567897',
                'reservation_time' => Carbon::yesterday()->setTime(20, 30, 0),
                'party_size' => 2,
                'status' => 'completed',
                'special_requests' => 'Anniversary dinner'
            ]
        ];

        foreach ($reservations as $reservationData) {
            // Find suitable table
            $suitableTable = $tables->where('capacity', '>=', $reservationData['party_size'])
                                   ->where('status', 'available')
                                   ->sortBy('capacity')
                                   ->first();

            if ($suitableTable && $reservationData['status'] === 'confirmed') {
                $reservationData['table_id'] = $suitableTable->id;
                // Update table status
                $suitableTable->update(['status' => 'reserved']);
            }

            Reservation::create($reservationData);
        }

        $this->command->info('Created ' . count($reservations) . ' sample reservations.');
    }
}
