<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GuideService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Админ-дашборд: все гайды с фильтрами.
 */
class DashboardController extends Controller
{
    public function __construct(
        private GuideService $guideService,
    ) {}

    /**
     * Список всех гайдов.
     */
    public function __invoke(Request $request): Response
    {
        $guideCategoryId = $request->filled('guide_category_id')
            ? (int) $request->input('guide_category_id')
            : null;
        $q = $request->string('q')->toString();

        $guides = $this->guideService->listGuides(
            ['q' => $q, 'guide_category_id' => $guideCategoryId],
            $request->user(),
        );

        return Inertia::render('Admin/Dashboard', [
            'guides' => $this->guideService->paginatorForInertia($guides),
            'categories' => $this->guideService->getCategoriesForSelect(),
            'filters' => [
                'guide_category_id' => $guideCategoryId,
                'q' => $q,
            ],
        ]);
    }
}
