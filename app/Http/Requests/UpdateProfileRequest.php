<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization checked in controller
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:50'],
            'last_name' => ['sometimes', 'string', 'max:50'],
            'bio' => ['sometimes', 'string', 'max:500'],
            'location' => ['sometimes', 'string', 'max:100'],
            'website' => ['sometimes', 'url', 'max:255'],
            'birth_date' => ['sometimes', 'date', 'before:today', 'after:1900-01-01'],
            'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // 2MB
            'cover_photo' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB
        ];
    }
}
