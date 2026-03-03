@extends('admin.layouts.app')

@section('title')
    <title>Акции</title>
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-navy-50">Акции</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-navy-200">
            Выберите только одну активную акцию. Одновременно две акции работать не могут.
        </p>
    </div>

    <form action="{{ route('promotions.update') }}" method="post" class="space-y-4">
        @csrf

        <div class="bg-white dark:bg-navy-800 rounded-xl shadow-sm border border-slate-200 dark:border-navy-600 p-6">
            <label class="flex items-start gap-3">
                <input
                    type="radio"
                    name="active_type"
                    value="{{ \App\Models\PromotionSetting::TYPE_PERCENT }}"
                    @checked(old('active_type', $promotion->active_type) === \App\Models\PromotionSetting::TYPE_PERCENT)
                    class="mt-1"
                />
                <div class="flex-1">
                    <div class="text-base font-medium text-slate-800 dark:text-navy-50">
                        Скидка в N % на все товары
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <input
                            type="number"
                            min="1"
                            max="100"
                            name="discount_percent"
                            id="discount_percent"
                            value="{{ old('discount_percent', $promotion->discount_percent) }}"
                            class="w-24 rounded-lg border border-slate-300 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                            placeholder="0"
                        />
                        <span class="text-sm text-slate-500">%</span>
                    </div>
                </div>
            </label>
        </div>

        <div class="bg-white dark:bg-navy-800 rounded-xl shadow-sm border border-slate-200 dark:border-navy-600 p-6">
            <label class="flex items-start gap-3">
                <input
                    type="radio"
                    name="active_type"
                    value="{{ \App\Models\PromotionSetting::TYPE_ONE_PLUS_TWO }}"
                    @checked(old('active_type', $promotion->active_type) === \App\Models\PromotionSetting::TYPE_ONE_PLUS_TWO)
                    class="mt-1"
                />
                <div class="flex-1">
                    <div class="text-base font-medium text-slate-800 dark:text-navy-50">
                        1+2 (две позиции дешевле первой — бесплатно)
                    </div>
                    <p class="mt-1 text-sm text-slate-500 dark:text-navy-200">
                        Покупатель оплачивает самый дорогой товар, два более дешёвых — по цене 0.
                    </p>
                </div>
            </label>
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg shadow-md transition"
            >
                Сохранить
            </button>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const percentRadio = document.querySelector('input[name="active_type"][value="{{ \App\Models\PromotionSetting::TYPE_PERCENT }}"]');
            const percentInput = document.getElementById('discount_percent');

            const togglePercent = () => {
                const isPercent = percentRadio.checked;
                percentInput.disabled = !isPercent;
                percentInput.classList.toggle('opacity-50', !isPercent);
            };

            document.querySelectorAll('input[name="active_type"]').forEach((item) => {
                item.addEventListener('change', togglePercent);
            });

            togglePercent();
        });
    </script>
@endsection
