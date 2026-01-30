<?php

declare(strict_types=1);

namespace App\Http\Requests\Post;

use App\Enums\PostType;
use App\Enums\PostVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:5000'],
            'type' => ['sometimes', Rule::enum(PostType::class)],
            'visibility' => ['sometimes', Rule::enum(PostVisibility::class)],
            'media' => ['sometimes', 'array', 'max:10'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,gif,mp4,mov', 'max:20480'],
        ];
    }
}
