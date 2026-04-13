<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Guide;
use App\Models\User;
use App\Repositories\Contracts\GuideRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;

/**
 * Бизнес-логика гайдов: списки для Inertia, CRUD, права редактирования.
 */
class GuideService
{
    public function __construct(
        private GuideRepositoryInterface $guideRepository,
    ) {}

    /**
     * Список гайдов через репозиторий + маппинг для Inertia (карточки).
     *
     * @param  array{q?: string, guide_category_id?: int|null, user_id?: int|null, per_page?: int}  $filters
     */
    public function listGuides(array $filters, ?User $viewer): LengthAwarePaginator
    {
        $paginator = $this->guideRepository->getAll($filters);

        return $paginator->through(fn (Guide $guide) => $this->toListItem($guide, $viewer));
    }

    /**
     * Paginator massiwine Inertia üçin «Öňki / Indiki» ýazgylaryny Türkmençe goşýar (app-locale nädogry bolsa hem).
     *
     * @return array<string, mixed>
     */
    public function paginatorForInertia(LengthAwarePaginator $paginator): array
    {
        $payload = $paginator->toArray();
        $payload['links'] = $this->paginationLinksWithTurkmenLabels($payload['links'] ?? []);

        return $payload;
    }

    /**
     * Previous/Next arkalyşyklaryny lang/tk/pagination.php boýunça düzýär.
     *
     * @param  list<array{url: ?string, label: string, active: bool}>  $links
     * @return list<array{url: ?string, label: string, active: bool}>
     */
    private function paginationLinksWithTurkmenLabels(array $links): array
    {
        return collect($links)
            ->map(function (array $link) {
                $label = (string) ($link['label'] ?? '');
                $plain = html_entity_decode(strip_tags($label), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $lower = mb_strtolower($plain);

                if (str_contains($lower, 'previous')) {
                    $link['label'] = Lang::get('pagination.previous', [], 'tk');
                } elseif (str_contains($lower, 'next')) {
                    $link['label'] = Lang::get('pagination.next', [], 'tk');
                }

                return $link;
            })
            ->all();
    }

    /**
     * Получить модель гайда по id (ULID).
     */
    public function getGuide(string $id): Guide
    {
        return $this->guideRepository->getById($id);
    }

    /**
     * Создать гайд от имени пользователя (admin или contributor).
     *
     * @param  array<string, mixed>  $validated
     */
    public function createGuide(array $validated, User $author): Guide
    {
        $steps = $this->parseSteps($validated['steps']);
        unset($validated['steps']);

        $payload = $this->attributesForCreate($author, $validated, $steps);

        return $this->guideRepository->create($payload);
    }

    /**
     * Обновить гайд (поля из Form Request; steps опциональны).
     *
     * @param  array<string, mixed>  $validated
     */
    public function updateGuide(Guide $guide, array $validated): Guide
    {
        $steps = array_key_exists('steps', $validated)
            ? $this->parseSteps((string) $validated['steps'])
            : $guide->steps;

        unset($validated['steps']);

        $payload = $this->buildUpdatePayload($guide, $validated, $steps);

        return $this->guideRepository->update($guide, $payload);
    }

    /**
     * Удалить гайд.
     */
    public function deleteGuide(Guide $guide): void
    {
        $this->guideRepository->delete($guide);
    }

    /**
     * Может ли пользователь редактировать/удалять гайд (как в политике).
     */
    public function canEdit(User $user, Guide $guide): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        if ($user->role !== UserRole::Contributor) {
            return false;
        }

        return $guide->user_id !== null && (int) $guide->user_id === (int) $user->id;
    }

    /**
     * Разбор шагов из многострочного текста.
     *
     * @return list<string>
     */
    public function parseSteps(string $raw): array
    {
        return collect(preg_split("/\r\n|\n|\r/", $raw))
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Имя автора для колонки author_name.
     */
    public function authorNameFor(User $user): string
    {
        return $user->role === UserRole::Admin ? 'Admin' : $user->name;
    }

    /**
     * Данные карточки гайда для Inertia.
     *
     * @return array<string, mixed>
     */
    public function toListItem(Guide $guide, ?User $viewer): array
    {
        $canManage = $viewer !== null && Gate::forUser($viewer)->allows('update', $guide);

        return [
            'id' => $guide->id,
            'title' => $guide->title,
            'guide_category_id' => $guide->guide_category_id,
            'category' => $guide->category?->name ?? 'Other',
            'category_color' => $guide->category?->color ?? 'gray',
            'description' => $guide->description,
            'tags' => $guide->tags,
            'author_name' => $guide->author_name,
            'can_manage' => $canManage,
            'updated_at' => $guide->updated_at?->toIso8601String(),
        ];
    }

    /**
     * Данные страницы просмотра гайда.
     *
     * @return array<string, mixed>
     */
    public function toDetail(Guide $guide, ?User $viewer): array
    {
        $canManage = $viewer !== null && Gate::forUser($viewer)->allows('update', $guide);

        return [
            'id' => $guide->id,
            'title' => $guide->title,
            'guide_category_id' => $guide->guide_category_id,
            'category' => $guide->category?->name ?? 'Other',
            'category_color' => $guide->category?->color ?? 'gray',
            'description' => $guide->description,
            'tags' => $guide->tags,
            'steps' => $guide->steps,
            'author_name' => $guide->author_name,
            'updated_at' => $guide->updated_at?->toIso8601String(),
            'can_manage' => $canManage,
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     * @param  list<string>  $steps
     * @return array<string, mixed>
     */
    private function attributesForCreate(User $user, array $validated, array $steps): array
    {
        return [
            'user_id' => $user->id,
            'guide_category_id' => (int) $validated['guide_category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'tags' => $validated['tags'] ?? [],
            'steps' => $steps,
            'author_name' => $this->authorNameFor($user),
        ];
    }

    /**
     * Слияние провалидированных полей с текущим гайдом (для sometimes).
     *
     * @param  array<string, mixed>  $validated
     * @param  list<string>|mixed  $steps
     * @return array<string, mixed>
     */
    private function buildUpdatePayload(Guide $guide, array $validated, array $steps): array
    {
        return [
            'title' => $validated['title'] ?? $guide->title,
            'guide_category_id' => (int) ($validated['guide_category_id'] ?? $guide->guide_category_id),
            'description' => $validated['description'] ?? $guide->description,
            'tags' => $validated['tags'] ?? $guide->tags,
            'steps' => $steps,
        ];
    }
}
