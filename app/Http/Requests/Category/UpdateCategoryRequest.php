<?php

namespace App\Http\Requests\Category;

use App\Enums\UserRole;
use App\Models\GuideCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Валидация обновления категории (только админ).
 */
class UpdateCategoryRequest extends FormRequest
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
        /** @var GuideCategory|null $category */
        $category = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('guide_categories', 'name')->ignore($category?->id),
            ],
            'color' => ['required', Rule::in(['blue', 'amber', 'green', 'orange', 'purple', 'sky', 'yellow', 'red', 'gray'])],
            'sort_order' => ['nullable', 'integer'],
        ];
    }
}
