<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ProductsExport;
use App\Http\Requests\ProductRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductController
{
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $categoryId = $request->query('category_id');
        $search = trim((string) $request->query('search', ''));

        $baseQuery = Product::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'ILIKE', "%{$search}%")
                        ->orWhere('name_uz', 'ILIKE', "%{$search}%")
                        ->orWhere('slug', 'ILIKE', "%{$search}%")
                        ->orWhere('id', $search);
                });
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });

        $products = (clone $baseQuery)
            ->with(['category:id,name'])
            ->orderBy('id')
            ->paginate(50)
            ->appends($request->query());


        $categories = Category::query()->select(['id', 'name'])->orderBy('name')->get();

        $stats = [
            'total_products' => (clone $baseQuery)->count(),
            'total_categories' => Category::count(),
            'avg_price' => (clone $baseQuery)->avg('price'),
            'active' => (clone $baseQuery)->where('is_active', true)->count(),
            'inactive' => (clone $baseQuery)->where('is_active', false)->count(),
        ];

        return view('admin.products.index', compact('products', 'stats', 'categories', 'categoryId', 'search'));
    }

    public function create(): View
    {
        $categories = Category::query()->select(['id', 'name'])->where('is_active', true)->get();
        $attributes = Attribute::query()->with('values')->orderBy('id')->get();

        return view('admin.products.create', compact('categories', 'attributes'));
    }

    public function show(Product $product): View
    {
        return view('admin.products.show', compact('product'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $this->service->create((array) $request->validated());

        return redirect()->route('products.index')->with('success', 'Продукт успешно добавлена!');
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()->select(['id', 'name'])->where('is_active', true)->get();
        $attributes = Attribute::query()->with('values')->orderBy('id')->get();
        $product->load('attributes');

        return view('admin.products.edit', compact('product', 'categories', 'attributes'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->service->update($product, (array) $request->validated());

        return redirect()->route('products.index')->with('success', 'Продукт успешно обнавлена!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->service->delete($product);

        return redirect()->route('products.index')->with('success', 'Продукт успешно удалена!');
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
