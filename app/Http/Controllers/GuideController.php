<?php

namespace App\Http\Controllers;

use App\Http\Requests\Guide\StoreGuideRequest;
use App\Http\Requests\Guide\UpdateGuideRequest;
use App\Models\Guide;
use App\Services\GuideService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Публичные гайды и CRUD контрибьютора по маршрутам / и /guides/*.
 */
class GuideController extends Controller
{
    public function __construct(
        private GuideService $guideService,
    ) {}

    /**
     * Главная: все гайды.
     */
    public function index(Request $request): Response
    {
        return $this->buildGuideListResponse($request, [], 'Gollanmalar', 'home');
    }

    /**
     * Гайды текущего пользователя.
     */
    public function myGuides(Request $request): Response
    {
        return $this->buildGuideListResponse(
            $request,
            ['user_id' => (int) $request->user()->id],
            'Mening gollanmalarym',
            'my-guides',
        );
    }

    /**
     * Общая логика списка гайдов для главной и «Мои гайды».
     *
     * @param  array<string, mixed>  $extraFilters
     */
    private function buildGuideListResponse(
        Request $request,
        array $extraFilters,
        string $pageTitle,
        string $listRouteName,
    ): Response {
        $filters = [
            'q' => $request->string('q')->toString(),
            'guide_category_id' => $request->filled('guide_category_id')
                ? (int) $request->input('guide_category_id')
                : null,
            ...$extraFilters,
        ];

        $guides = $this->guideService->listGuides($filters, $request->user());

        return Inertia::render('Home', [
            'guides' => $this->guideService->paginatorForInertia($guides),
            'categories' => $this->guideService->getCategoriesForSelect(),
            'filters' => $filters,
            'pageTitle' => $pageTitle,
            'listRouteName' => $listRouteName,
        ]);
    }

    /**
     * Просмотр одного гайда.
     */
    public function show(Request $request, Guide $guide): Response
    {
        return Inertia::render('Guide', [
            'guide' => $this->guideService->toDetail($guide, $request->user()),
        ]);
    }

    /**
     * Форма создания (контрибьютор / при необходимости админ с публичной формы).
     */
    public function create(): Response
    {
        $this->authorize('create', Guide::class);

        return Inertia::render('GuideForm', [
            'guide' => null,
            'categories' => $this->guideService->getCategoriesForSelect(),
            'formContext' => 'contributor',
            'cancelTo' => ['name' => 'home'],
        ]);
    }

    /**
     * Сохранение нового гайда.
     */
    public function store(StoreGuideRequest $request): RedirectResponse
    {
        $this->guideService->createGuide($request->validated(), $request->user());

        return redirect()->route('home');
    }

    /**
     * Форма редактирования.
     */
    public function edit(Guide $guide): Response
    {
        $this->authorize('update', $guide);

        return Inertia::render('GuideForm', [
            'guide' => [
                'id' => $guide->id,
                'title' => $guide->title,
                'guide_category_id' => $guide->guide_category_id,
                'description' => $guide->description,
                'tags' => $guide->tags,
                'steps' => implode("\n", $guide->steps),
            ],
            'categories' => $this->guideService->getCategoriesForSelect(),
            'formContext' => 'contributor',
            'cancelTo' => [
                'name' => 'guides.show',
                'params' => ['guide' => $guide->id],
            ],
        ]);
    }

    /**
     * Обновление гайда.
     */
    public function update(UpdateGuideRequest $request, Guide $guide): RedirectResponse
    {
        $this->guideService->updateGuide($guide, $request->validated());

        return redirect()->route('guides.show', $guide);
    }

    /**
     * Удаление гайда.
     */
    public function destroy(Guide $guide): RedirectResponse
    {
        $this->authorize('delete', $guide);

        $this->guideService->deleteGuide($guide);

        return redirect()->route('home');
    }
}
