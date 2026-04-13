<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guide\StoreGuideRequest;
use App\Http\Requests\Guide\UpdateGuideRequest;
use App\Models\Guide;
use App\Services\GuideService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * CRUD гайдов в админке (/admin/guides/*).
 */
class AdminGuideController extends Controller
{
    public function __construct(
        private GuideService $guideService,
    ) {}

    /**
     * Форма создания гайда.
     */
    public function create(): Response
    {
        return Inertia::render('GuideForm', [
            'guide' => null,
            'categories' => $this->guideService->getCategoriesForSelect(),
            'formContext' => 'admin',
            'cancelTo' => ['name' => 'admin.dashboard'],
        ]);
    }

    /**
     * Сохранение нового гайда.
     */
    public function store(StoreGuideRequest $request): RedirectResponse
    {
        $this->guideService->createGuide($request->validated(), $request->user());

        return redirect()->route('admin.dashboard');
    }

    /**
     * Форма редактирования.
     */
    public function edit(Guide $guide): Response
    {
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
            'formContext' => 'admin',
            'cancelTo' => ['name' => 'admin.dashboard'],
        ]);
    }

    /**
     * Обновление гайда.
     */
    public function update(UpdateGuideRequest $request, Guide $guide): RedirectResponse
    {
        $this->guideService->updateGuide($guide, $request->validated());

        return redirect()->route('admin.dashboard');
    }

    /**
     * Удаление гайда.
     */
    public function destroy(Guide $guide): RedirectResponse
    {
        $this->guideService->deleteGuide($guide);

        return redirect()->route('admin.dashboard');
    }
}
