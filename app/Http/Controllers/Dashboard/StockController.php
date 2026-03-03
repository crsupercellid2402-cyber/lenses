<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\StockRequest;
use App\Models\Stock;
use App\Services\StockService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StockController
{
    protected StockService $service;

    public function __construct(StockService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $stockFilter = $request->string('stock_filter')->toString();
        $search = trim((string) $request->query('search', ''));
        $allowedFilters = ['', 'out', 'low', 'ok', 'restock'];

        if (!in_array($stockFilter, $allowedFilters, true)) {
            $stockFilter = '';
        }

        $stocks = Stock::with('product')
            ->select('*')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('id', $search)
                        ->orWhere('product_id', $search)
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery->where('name', 'ILIKE', "%{$search}%")
                                ->orWhere('name_uz', 'ILIKE', "%{$search}%")
                                ->orWhere('slug', 'ILIKE', "%{$search}%");
                        });
                });
            })
            ->when($stockFilter === 'out', fn ($query) => $query->where('quantity', 0))
            ->when($stockFilter === 'low', fn ($query) => $query->whereBetween('quantity', [1, 5]))
            ->when($stockFilter === 'restock', fn ($query) => $query->where('quantity', '<=', 5))
            ->when($stockFilter === 'ok', fn ($query) => $query->where('quantity', '>', 5))
            ->orderByRaw('
            CASE
                WHEN quantity = 0 THEN 0
                WHEN quantity <= 5 THEN 1
                ELSE 2
            END
        ')
            ->orderBy('id')
            ->paginate(50)
            ->withQueryString();

        return view('admin.stocks.index', compact('stocks', 'stockFilter', 'search'));
    }

    public function edit(Stock $stock): View
    {
        $histories = $stock->history;

        return view('admin.stocks.edit', compact('stock', 'histories'));
    }

    public function show(Stock $stock, Request $request): View
    {
        $history = $stock->history()
            ->when($request->source, fn ($q) => $q->where('source', $request->source))
            ->orderByDesc('created_at')
            ->get();

        return view('admin.stocks.show', compact('stock', 'history'));
    }

    public function update(Stock $stock, StockRequest $request): RedirectResponse
    {
        $this->service->update($stock, (array) $request->validated());

        return redirect()->route('stocks.index')->with('success', 'Остатки успешно обнавлены!');
    }
}
