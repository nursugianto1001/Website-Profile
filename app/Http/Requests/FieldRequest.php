<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FieldRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pastikan hanya admin yang bisa mengelola lapangan
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
        ];
    }
}
