<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Post::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:5000'],
            'visibility' => ['sometimes', 'string', 'in:public,friends,private'],
            'media' => ['sometimes', 'array', 'max:10'],
            'media.*' => ['file', 'mimes:jpeg,png,jpg,webp,mp4,mov', 'max:51200'], // 50MB
        ];
    }
}
