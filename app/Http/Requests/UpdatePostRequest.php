<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $post = $this->route('post');
        return $this->user()?->can('update', $post) ?? false;
    }

    public function rules(): array
    {
        return [
            'content' => ['sometimes', 'string', 'max:5000'],
            'visibility' => ['sometimes', 'string', 'in:public,friends,private'],
        ];
    }
}
