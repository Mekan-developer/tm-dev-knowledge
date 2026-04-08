<?php

namespace App\Http\Requests\Guide;

use App\Models\Guide;
use App\Services\GuideService;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Валидация обновления гайда; авторизация через canEdit в сервисе.
 */
class UpdateGuideRequest extends FormRequest
{
    /**
     * Доступ только если пользователь может редактировать этот гайд.
     */
    public function authorize(): bool
    {
        $guide = $this->route('guide');
        if (! $guide instanceof Guide || $this->user() === null) {
            return false;
        }

        return app(GuideService::class)->canEdit($this->user(), $guide);
    }

    /**
     * Частичное обновление: поля с префиксом sometimes (форма Vue шлёт все поля).
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'guide_category_id' => ['sometimes', 'required', 'exists:guide_categories,id'],
            'description' => ['sometimes', 'required', 'string'],
            'tags' => ['sometimes', 'nullable', 'array'],
            'tags.*' => ['string', 'max:64'],
            'steps' => ['sometimes', 'required', 'string'],
        ];
    }
}
