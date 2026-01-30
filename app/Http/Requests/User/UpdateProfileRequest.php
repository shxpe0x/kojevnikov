<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'bio' => ['sometimes', 'string', 'max:500'],
            'location' => ['sometimes', 'string', 'max:100'],
            'website' => ['sometimes', 'url', 'max:255'],
            'birth_date' => ['sometimes', 'date', 'before:today'],
            'avatar' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'cover_photo' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ];
    }
}
