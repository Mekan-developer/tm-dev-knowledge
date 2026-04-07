<?php

namespace App\Http\Controllers\Admin;

use App\Enums\GuideCategory;
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
        $category = $request->string('category', 'All')->toString();
        $q = $request->string('q')->toString();

        $guides = $this->guideService->listGuides(
            ['q' => $q, 'category' => $category],
            $request->user(),
        );

        return Inertia::render('Admin/Dashboard', [
            'guides' => $guides,
            'categories' => array_merge(['All'], GuideCategory::values()),
            'filters' => [
                'category' => $category,
                'q' => $q,
            ],
        ]);
    }
}
