<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Field;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Pagi',
            'email' => 'admin@gmail.com',
            'usertype' => 'admin',
            'password' => bcrypt('dsadsadsa'),
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'name' => 'Admin Sore',
            'email' => 'adminsore@email.com',
            'usertype' => 'admin',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'name' => 'Admin Malam',
            'email' => 'adminmalam@email.com',
            'usertype' => 'admin',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $fields = [
            [
                'name' => 'Lapangan Badminton 1',
                'description' => 'Lapangan Badmin berukuran standar dengan karet berkualitas tinggi',
                'price_per_hour' => 40000, // Base price untuk compatibility
                'image_url' => '/storage/fields/futsal-a.jpg',
                'is_active' => true,
                'opening_hour' => 6,
                'closing_hour' => 23,
                // Tambahan untuk sistem harga dinamis
                'price_morning' => 40000,   // 06:00-12:00
                'price_afternoon' => 25000, // 12:00-17:00  
                'price_evening' => 60000,   // 17:00-23:00
            ],
            [
                'name' => 'Lapangan Badminton 2',
                'description' => 'Lapangan Badmin berukuran standar dengan karet berkualitas tinggi',
                'price_per_hour' => 40000,
                'image_url' => '/storage/fields/futsal-b.jpg',
                'is_active' => true,
                'opening_hour' => 6,
                'closing_hour' => 23,
                'price_morning' => 40000,
                'price_afternoon' => 25000,
                'price_evening' => 60000,
            ],
            [
                'name' => 'Lapangan Badminton 3',
                'description' => 'Lapangan Badmin berukuran standar dengan karet berkualitas tinggi',
                'price_per_hour' => 40000,
                'image_url' => '/storage/fields/futsal-c.jpg',
                'is_active' => true,
                'opening_hour' => 6,
                'closing_hour' => 23,
                'price_morning' => 40000,
                'price_afternoon' => 25000,
                'price_evening' => 60000,
            ],
            [
                'name' => 'Lapangan Badminton 4',
                'description' => 'Lapangan Badmin berukuran standar dengan karet berkualitas tinggi',
                'price_per_hour' => 40000,
                'image_url' => '/storage/fields/basket-a.jpg',
                'is_active' => true,
                'opening_hour' => 6,
                'closing_hour' => 23,
                'price_morning' => 40000,
                'price_afternoon' => 25000,
                'price_evening' => 60000,
            ],
            [
                'name' => 'Lapangan Badminton 5',
                'description' => 'Lapangan badminton dengan lantai vinyl profesional dan pencahayaan standar turnamen',
                'price_per_hour' => 40000,
                'image_url' => '/storage/fields/badminton.jpg',
                'is_active' => true,
                'opening_hour' => 6,
                'closing_hour' => 23,
                'price_morning' => 40000,
                'price_afternoon' => 25000,
                'price_evening' => 60000,
            ],
            [
                'name' => 'Lapangan Badminton 6',
                'description' => 'Lapangan Badmin berukuran standar dengan karet berkualitas tinggi',
                'price_per_hour' => 40000,
                'image_url' => '/storage/fields/tennis.jpg',
                'is_active' => true,
                'opening_hour' => 6,
                'closing_hour' => 23,
                'price_morning' => 40000,
                'price_afternoon' => 25000,
                'price_evening' => 60000,
            ],
        ];

        foreach ($fields as $fieldData) {
            Field::create($fieldData);
        }
    }
}
