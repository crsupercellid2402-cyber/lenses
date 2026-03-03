<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController
{
    protected CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(): View
    {
        $categories = Category::query()->select([
            'id', 'name', 'name_uz', 'slug', 'description', 'description_uz', 'is_active',
            'discount_percent'
        ])
            ->with('parentRecursive')
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(50);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $categoriesTree = Category::query()
            ->whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('categoriesTree'));
    }

    public function show(Category $category): View
    {
        return view('admin.categories.show', compact('category'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->service->create((array) $request->validated());

        return redirect()->route('categories.index')->with('success', 'Категория успешно добавлена!');
    }

    public function edit(Category $category): View
    {
        $categoriesTree = Category::query()
            ->whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('name')
            ->get();

        $excludedCategoryIds = $category->selfAndDescendantIds();

        return view('admin.categories.edit', compact('category', 'categoriesTree', 'excludedCategoryIds'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->service->update($category, (array) $request->validated());

        return redirect()->route('categories.index')->with('success', 'Категория успешно обнавлена!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->service->delete($category);

        return redirect()->route('categories.index')->with('success', 'Категория успешно удалена!');
    }
}
