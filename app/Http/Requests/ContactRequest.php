<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Валидация публичной формы обратной связи.
 */
class ContactRequest extends FormRequest
{
    /**
     * Форма доступна всем посетителям без авторизации.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:20', 'max:1000'],
            'website' => ['nullable', 'max:0'],
        ];
    }
}
