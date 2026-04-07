<?php

namespace App\Http\Requests\Guide;

use App\Enums\GuideCategory;
use App\Models\Guide;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * Валидация создания гайда (контрибьютор и админ).
 */
class StoreGuideRequest extends FormRequest
{
    /**
     * Разрешено только авторизованным с правом create (политика гайда).
     */
    public function authorize(): bool
    {
        return $this->user() !== null && Gate::forUser($this->user())->allows('create', Guide::class);
    }

    /**
     * Правила совместимы с текущей формой Vue (steps — многострочная строка).
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(GuideCategory::class)],
            'description' => ['required', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:64'],
            'steps' => ['required', 'string'],
        ];
    }
}
