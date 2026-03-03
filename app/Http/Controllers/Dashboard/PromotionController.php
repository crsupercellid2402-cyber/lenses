<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\PromotionSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromotionController
{
    public function index(): View
    {
        $promotion = PromotionSetting::query()->first();

        if (!$promotion) {
            $promotion = PromotionSetting::create([
                'active_type' => PromotionSetting::TYPE_PERCENT,
                'discount_percent' => null,
            ]);
        }

        return view('admin.promotions.index', compact('promotion'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'active_type' => ['required', Rule::in([
                PromotionSetting::TYPE_PERCENT,
                PromotionSetting::TYPE_ONE_PLUS_TWO,
            ])],
            'discount_percent' => [
                'nullable',
                Rule::requiredIf(
                    $request->input('active_type') === PromotionSetting::TYPE_PERCENT
                ),
                'integer',
                'min:1',
                'max:100',
            ],
        ]);

        if ($data['active_type'] !== PromotionSetting::TYPE_PERCENT) {
            $data['discount_percent'] = null;
        }

        $promotion = PromotionSetting::query()->first();

        if (!$promotion) {
            PromotionSetting::create($data);
        } else {
            $promotion->update($data);
        }

        return redirect()
            ->route('promotions.index')
            ->with('success', 'Акция обновлена');
    }
}
