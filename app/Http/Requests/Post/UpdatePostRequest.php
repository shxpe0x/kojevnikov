<?php

declare(strict_types=1);

namespace App\Http\Requests\Post;

use App\Enums\PostVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['sometimes', 'string', 'max:5000'],
            'visibility' => ['sometimes', Rule::enum(PostVisibility::class)],
        ];
    }
}
