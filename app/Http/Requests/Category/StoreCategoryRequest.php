<?php

namespace App\Http\Requests\Category;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Валидация создания категории (только админ).
 */
class StoreCategoryRequest extends FormRequest
{
    /**
     * Разрешено только администратору.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'unique:guide_categories,name'],
            'color' => ['required', Rule::in(['blue', 'amber', 'green', 'orange', 'purple', 'sky', 'yellow', 'red', 'gray'])],
            'sort_order' => ['nullable', 'integer'],
        ];
    }
}
