<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pastikan hanya pengguna yang diizinkan yang dapat melakukan booking
    }

    public function rules()
    {
        return [
            'field_id' => 'required|exists:fields,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date' => 'required|date|after_or_equal:today',
            'slots' => 'required|array',
            'slots.*' => 'date_format:H:i',
        ];
    }
}
