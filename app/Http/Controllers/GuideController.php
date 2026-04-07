<?php

namespace App\Http\Controllers;

use App\Enums\GuideCategory;
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
        $category = $request->string('category', 'All')->toString();
        $q = $request->string('q')->toString();

        $guides = $this->guideService->listGuides(
            ['q' => $q, 'category' => $category],
            $request->user(),
        );

        return Inertia::render('Home', [
            'guides' => $guides,
            'categories' => array_merge(['All'], GuideCategory::values()),
            'filters' => [
                'category' => $category,
                'q' => $q,
            ],
            'pageTitle' => 'Guides',
            'listRouteName' => 'home',
        ]);
    }

    /**
     * Гайды текущего пользователя.
     */
    public function myGuides(Request $request): Response
    {
        $category = $request->string('category', 'All')->toString();
        $q = $request->string('q')->toString();

        $guides = $this->guideService->listGuides(
            [
                'q' => $q,
                'category' => $category,
                'user_id' => (int) $request->user()->id,
            ],
            $request->user(),
        );

        return Inertia::render('Home', [
            'guides' => $guides,
            'categories' => array_merge(['All'], GuideCategory::values()),
            'filters' => [
                'category' => $category,
                'q' => $q,
            ],
            'pageTitle' => 'My guides',
            'listRouteName' => 'my-guides',
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
            'categories' => GuideCategory::values(),
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
                'category' => $guide->category->value,
                'description' => $guide->description,
                'tags' => $guide->tags,
                'steps' => implode("\n", $guide->steps),
            ],
            'categories' => GuideCategory::values(),
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
    public function destroy(Request $request, Guide $guide): RedirectResponse
    {
        abort_unless($this->guideService->canEdit($request->user(), $guide), 403);

        $this->guideService->deleteGuide($guide);

        return redirect()->route('home');
    }
}
