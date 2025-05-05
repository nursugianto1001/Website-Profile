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
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'usertype' => 'admin',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $fields = [
            [
                'name' => 'Lapangan Futsal A',
                'description' => 'Lapangan futsal berukuran standar dengan rumput sintetis berkualitas tinggi',
                'price_per_hour' => 150000,
                'image_url' => '/storage/fields/futsal-a.jpg',
                'is_active' => true,
                'opening_hour' => 8,
                'closing_hour' => 22,
            ],
            [
                'name' => 'Lapangan Futsal B',
                'description' => 'Lapangan futsal indoor dengan lantai vinyl dan sistem pendingin udara',
                'price_per_hour' => 200000,
                'image_url' => '/storage/fields/futsal-b.jpg',
                'is_active' => true,
                'opening_hour' => 8,
                'closing_hour' => 23,
            ],
            [
                'name' => 'Lapangan Futsal C',
                'description' => 'Lapangan futsal outdoor dengan rumput sintetis dan lampu sorot',
                'price_per_hour' => 120000,
                'image_url' => '/storage/fields/futsal-c.jpg',
                'is_active' => true,
                'opening_hour' => 9,
                'closing_hour' => 21,
            ],
            [
                'name' => 'Lapangan Basket A',
                'description' => 'Lapangan basket indoor dengan lantai kayu dan tribun penonton',
                'price_per_hour' => 250000,
                'image_url' => '/storage/fields/basket-a.jpg',
                'is_active' => true,
                'opening_hour' => 8,
                'closing_hour' => 22,
            ],
            [
                'name' => 'Lapangan Badminton',
                'description' => 'Lapangan badminton dengan lantai vinyl profesional dan pencahayaan standar turnamen',
                'price_per_hour' => 100000,
                'image_url' => '/storage/fields/badminton.jpg',
                'is_active' => true,
                'opening_hour' => 8,
                'closing_hour' => 21,
            ],
            [
                'name' => 'Lapangan Tennis',
                'description' => 'Lapangan tennis outdoor dengan permukaan hard court dan lampu sorot',
                'price_per_hour' => 180000,
                'image_url' => '/storage/fields/tennis.jpg',
                'is_active' => false,
                'opening_hour' => 7,
                'closing_hour' => 20,
            ],
        ];

        foreach ($fields as $fieldData) {
            Field::create($fieldData);
        }

        $customers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
            ],
            [
                'name' => 'Ani Wijaya',
                'email' => 'ani@example.com',
                'phone' => '081234567891',
            ],
            [
                'name' => 'Dedi Kurniawan',
                'email' => 'dedi@example.com',
                'phone' => '081234567892',
            ],
            [
                'name' => 'Fina Putri',
                'email' => 'fina@example.com',
                'phone' => '081234567893',
            ],
            [
                'name' => 'Hendra Setiawan',
                'email' => 'hendra@example.com',
                'phone' => '081234567894',
            ],
        ];

        $today = Carbon::today();

        for ($i = 1; $i <= 10; $i++) {
            $fieldId = rand(1, 5);
            $field = Field::find($fieldId);
            $customer = $customers[array_rand($customers)];
            $bookingDate = $today->copy()->subDays(rand(1, 30))->format('Y-m-d');

            $openingHour = $field->opening_hour;
            $closingHour = min($field->closing_hour - 1, 21);
            $startHour = rand($openingHour, $closingHour);
            $duration = min(3, $field->closing_hour - $startHour);

            $startTime = sprintf('%02d:00:00', $startHour);
            $endTime = sprintf('%02d:00:00', $startHour + $duration);

            $startDateTime = Carbon::parse($startTime);
            $endDateTime = Carbon::parse($endTime);
            $durationHours = $endDateTime->diffInHours($startDateTime);

            $booking = Booking::create([
                'field_id' => $fieldId,
                'customer_name' => $customer['name'],
                'customer_email' => $customer['email'],
                'customer_phone' => $customer['phone'],
                'booking_date' => $bookingDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'payment_method' => rand(0, 1) ? 'online' : 'cash',
                'payment_status' => 'settlement',
                'total_price' => $field->price_per_hour * $durationHours,
                'duration_hours' => $durationHours,
                'snap_token' => 'tok_' . Str::random(16),
            ]);

            $this->createBookingSlots($booking, $durationHours);

            $this->createTransaction($booking, 'settlement');
        }

        for ($i = 1; $i <= 5; $i++) {
            $fieldId = rand(1, 5);
            $field = Field::find($fieldId);
            $customer = $customers[array_rand($customers)];

            $openingHour = $field->opening_hour;
            $closingHour = min($field->closing_hour - 1, 21);
            $startHour = rand($openingHour, $closingHour);
            $duration = min(2, $field->closing_hour - $startHour);
            $startTime = sprintf('%02d:00:00', $startHour);
            $endTime = sprintf('%02d:00:00', $startHour + $duration);

            $startDateTime = Carbon::parse($startTime);
            $endDateTime = Carbon::parse($endTime);
            $durationHours = $endDateTime->diffInHours($startDateTime);

            $booking = Booking::create([
                'field_id' => $fieldId,
                'customer_name' => $customer['name'],
                'customer_email' => $customer['email'],
                'customer_phone' => $customer['phone'],
                'booking_date' => $today->format('Y-m-d'),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'payment_method' => rand(0, 1) ? 'online' : 'cash',
                'payment_status' => 'settlement',
                'total_price' => $field->price_per_hour * $durationHours,
                'duration_hours' => $durationHours,
                'snap_token' => 'tok_' . Str::random(16),
            ]);

            $this->createBookingSlots($booking, $durationHours);

            $this->createTransaction($booking, 'settlement');
        }

        for ($i = 1; $i <= 15; $i++) {
            $fieldId = rand(1, 6);
            $field = Field::find($fieldId);
            $customer = $customers[array_rand($customers)];
            $bookingDate = $today->copy()->addDays(rand(1, 14))->format('Y-m-d');

            $openingHour = $field->opening_hour;
            $closingHour = min($field->closing_hour - 1, 21);
            $startHour = rand($openingHour, $closingHour);
            $duration = min(3, $field->closing_hour - $startHour);

            $startTime = sprintf('%02d:00:00', $startHour);
            $endTime = sprintf('%02d:00:00', $startHour + $duration);

            $startDateTime = Carbon::parse($startTime);
            $endDateTime = Carbon::parse($endTime);
            $durationHours = $endDateTime->diffInHours($startDateTime);

            $paymentMethod = rand(0, 1) ? 'online' : 'cash';
            $paymentStatus = rand(0, 1) ? 'settlement' : 'pending';

            $booking = Booking::create([
                'field_id' => $fieldId,
                'customer_name' => $customer['name'],
                'customer_email' => $customer['email'],
                'customer_phone' => $customer['phone'],
                'booking_date' => $bookingDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'total_price' => $field->price_per_hour * $durationHours,
                'duration_hours' => $durationHours,
                'snap_token' => $paymentMethod === 'online' ? 'tok_' . Str::random(16) : null,
            ]);

            $this->createBookingSlots($booking, $durationHours);

            $this->createTransaction($booking, $paymentStatus);
        }

        for ($i = 1; $i <= 5; $i++) {
            $fieldId = rand(1, 6);
            $field = Field::find($fieldId);
            $customer = $customers[array_rand($customers)];
            $bookingDate = $today->copy()->addDays(rand(-10, 10))->format('Y-m-d');

            $openingHour = $field->opening_hour;
            $closingHour = min($field->closing_hour - 1, 21);
            $startHour = rand($openingHour, $closingHour);
            $duration = min(3, $field->closing_hour - $startHour);

            $startTime = sprintf('%02d:00:00', $startHour);
            $endTime = sprintf('%02d:00:00', $startHour + $duration);

            $startDateTime = Carbon::parse($startTime);
            $endDateTime = Carbon::parse($endTime);
            $durationHours = $endDateTime->diffInHours($startDateTime);

            $booking = Booking::create([
                'field_id' => $fieldId,
                'customer_name' => $customer['name'],
                'customer_email' => $customer['email'],
                'customer_phone' => $customer['phone'],
                'booking_date' => $bookingDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'payment_method' => rand(0, 1) ? 'online' : 'cash',
                'payment_status' => 'expired',
                'total_price' => $field->price_per_hour * $durationHours,
                'duration_hours' => $durationHours,
                'snap_token' => 'tok_' . Str::random(16),
            ]);

            $this->createBookingSlots($booking, $durationHours, 'cancelled');

            $this->createTransaction($booking, 'expired');
        }
    }

    /**
     * 
     *
     * @param Booking $booking
     * @param int $duration
     * @param string|null $overrideStatus
     * @return void
     */
    private function createBookingSlots(Booking $booking, int $duration, ?string $overrideStatus = null): void
    {
        $startDateTime = Carbon::parse($booking->start_time);

        $slotStatus = 'booked';
        if ($overrideStatus === 'cancelled' || in_array($booking->payment_status, ['expired', 'cancel', 'failure', 'deny'])) {
            $slotStatus = 'cancelled';
        }

        for ($hour = 0; $hour < $duration; $hour++) {
            $slotStartTime = $startDateTime->copy()->addHours($hour)->format('H:i:s');
            $slotEndTime = $startDateTime->copy()->addHours($hour + 1)->format('H:i:s');

            BookingSlot::create([
                'booking_id' => $booking->id,
                'field_id' => $booking->field_id,
                'booking_date' => $booking->booking_date,
                'start_time' => $slotStartTime,
                'end_time' => $slotEndTime,
                'status' => $slotStatus,
            ]);
        }
    }

    /**
     * Create transaction for a booking
     *
     * @param Booking $booking
     * @param string $status
     * @return void
     */
    private function createTransaction(Booking $booking, string $status): void
    {
        $orderId = 'ORD-' . strtoupper(Str::random(8));

        $paymentTypes = [
            'credit_card',
            'cstore',
            'bank_transfer',
            'echannel',
            'bca_klikpay',
            'bca_klikbca',
            'bri_epay',
            'gopay',
            'shopeepay'
        ];

        if ($booking->payment_method === 'online') {
            $paymentType = $paymentTypes[rand(0, count($paymentTypes) - 1)];
        } else {
            $paymentType = 'cash';
        }

        Transaction::create([
            'booking_id' => $booking->id,
            'order_id' => $orderId,
            'gross_amount' => $booking->total_price,
            'payment_type' => $paymentType,
            'transaction_status' => $status,
            'transaction_time' => now(),
            'payment_channel' => $booking->payment_method === 'online' ? 'Midtrans' : 'Manual',
            'snap_token' => $booking->payment_method === 'online' ? 'tok_' . Str::random(16) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
