<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\GuideCategory;
use App\Services\GuideCategoryService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function __construct(
        private GuideCategoryService $guideCategoryService,
    ) {}

    /**
     * Показывает список категорий и количество гайдов в каждой категории.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Categories', [
            'categories' => $this->guideCategoryService->getAllCategories(),
        ]);
    }

    /**
     * Создает новую категорию.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->guideCategoryService->createCategory($request->validated());

        return back()->with('success', 'Категория добавлена.');
    }

    /**
     * Обновляет категорию.
     */
    public function update(UpdateCategoryRequest $request, GuideCategory $category): RedirectResponse
    {
        $this->guideCategoryService->updateCategory($category, $request->validated());

        return back()->with('success', 'Категория обновлена.');
    }

    /**
     * Удаляет категорию, если в ней нет гайдов.
     */
    public function destroy(GuideCategory $category): RedirectResponse
    {
        try {
            $this->guideCategoryService->deleteCategory($category);

            return back()->with('success', 'Категория удалена.');
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Сохраняет новый порядок категорий.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $ids = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:guide_categories,id'],
        ])['ids'];

        $this->guideCategoryService->reorderCategories($ids);

        return back()->with('success', 'Порядок категорий обновлен.');
    }
}
