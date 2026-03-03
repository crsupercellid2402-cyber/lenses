@extends('admin.layouts.app')

@section('title')
    <title>Добавление продукта</title>
@endsection

@section('content')
    <div class="py-8">
        <!-- Заголовок -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-slate-800 dark:text-navy-50">
                Добавление продукта
            </h1>

            <a href="{{ route('products.index') }}"
                class="text-sm text-slate-500 hover:text-slate-700 dark:text-navy-200 transition">
                ← Назад к списку
            </a>
        </div>

        <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data"
            class="bg-white dark:bg-navy-700 rounded-2xl shadow-md dark:shadow-[0_0_10px_rgba(0,0,0,0.2)] p-6 space-y-5 transition">
            @csrf

            <h2 class="text-lg font-semibold text-slate-800 mb-4">
                Информация о продукте
            </h2>

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Производитель
                        (manufacturer)</label>
                    <input type="text" name="manufacturer" value="{{ old('manufacturer') }}"
                        class="w-full rounded-lg border border-slate-300 dark:border-navy-500 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Артикул
                        (article)</label>
                    <input type="text" name="article" value="{{ old('article') }}"
                        class="w-full rounded-lg border border-slate-300 dark:border-navy-500 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Модель (model)</label>
                    <input type="text" name="model" value="{{ old('model') }}"
                        class="w-full rounded-lg border border-slate-300 dark:border-navy-500 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Покрытие
                        (coating)</label>
                    <input type="text" name="coating" value="{{ old('coating') }}"
                        class="w-full rounded-lg border border-slate-300 dark:border-navy-500 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Индекс (index)</label>
                    <input type="number" step="any" name="index" value="{{ old('index') }}"
                        class="w-full rounded-lg border border-slate-300 dark:border-navy-500 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Семейство
                        (family)</label>
                    <input type="text" name="family" value="{{ old('family') }}"
                        class="w-full rounded-lg border border-slate-300 dark:border-navy-500 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Цвет (color)</label>
                    <input type="text" name="color" value="{{ old('color') }}"
                        class="w-full rounded-lg border border-slate-300 dark:border-navy-500 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Опция (option)</label>
                    <input type="text" name="option" value="{{ old('option') }}"
                        class="w-full rounded-lg border border-slate-300 dark:border-navy-500 bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-5 mt-6">
                <div>
                    <label class="block text-sm font-medium text-slate-800 mb-1">SPH (Сфера)</label>
                    <input type="number" step="any" name="sph" value="{{ old('sph') }}"
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 mb-1">CYL (Цилиндр)</label>
                    <input type="number" step="any" name="cyl" value="{{ old('cyl') }}"
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 mb-1">AXIS (Ось)</label>
                    <input type="number" step="any" name="axis" value="{{ old('axis') }}"
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 mb-1">ADD (Аддидация)</label>
                    <input type="number" step="any" name="add" value="{{ old('add') }}"
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-800 mb-1">PRISM (Призма)</label>
                    <input type="number" step="any" name="prism" value="{{ old('prism') }}"
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>
            </div>

            <div class="col-span-2 flex items-center gap-4 mt-2">
                <button type="button" id="transpose-btn"
                    class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Транспонировать</button>
                <span id="transpose-result" class="text-sm text-slate-700"></span>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Название</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border border-slate-300 dark:border-navy-500
                          bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm
                          focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Название UZ</label>
                <input type="text" name="name_uz" value="{{ old('name_uz') }}" required
                    class="w-full rounded-lg border border-slate-300 dark:border-navy-500
                          bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm
                          focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Категория</label>
                <select name="category_id" required
                    class="w-full rounded-lg border border-slate-300 dark:border-navy-500
                           bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm
                           focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    <option value="">Выберите категорию</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Цена (сум)</label>
                <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01"
                    required
                    class="w-full rounded-lg border border-slate-300 dark:border-navy-500
                          bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm
                          focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Скидка (%)</label>
                <input type="number" name="discount_percent" value="{{ old('discount_percent') }}" min="0"
                    max="100" step="1"
                    class="w-full rounded-lg border border-slate-300 dark:border-navy-500
                          bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm
                          focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                    placeholder="0">
            </div>

            <div class="flex items-center space-x-2 pt-2">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active'))
                    class="w-4 h-4 rounded border-slate-400 text-blue-600 focus:ring-blue-500
                          dark:bg-navy-800 dark:border-navy-500 dark:checked:bg-blue-600">
                <label class="text-sm text-slate-700 dark:text-slate-800">Активен</label>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Описание</label>
                <textarea name="description" rows="3" required
                    class="w-full rounded-lg border border-slate-300 dark:border-navy-500
                         bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm resize-none
                         focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-1">Описание UZ</label>
                <textarea name="description_uz" rows="3" required
                    class="w-full rounded-lg border border-slate-300 dark:border-navy-500
                         bg-slate-50 dark:bg-navy-800 px-3 py-2 text-slate-800 dark:text-slate-800 text-sm resize-none
                         focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">{{ old('description_uz') }}</textarea>
            </div>

            @if ($attributes->isNotEmpty())
                <div>
                    <h2 class="text-lg font-semibold text-slate-800 mb-3">Атрибуты</h2>
                    <div class="grid sm:grid-cols-2 gap-5">
                        @foreach ($attributes as $attribute)
                            <div>
                                <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-2">
                                    {{ $attribute->name }}
                                </label>

                                <div class="flex flex-wrap gap-2">
                                    @foreach ($attribute->values as $value)
                                        <label
                                            class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-800">
                                            <input type="checkbox" name="attributes[{{ $attribute->id }}][]"
                                                value="{{ $value->value }}" @checked(collect(old('attributes.' . $attribute->id, []))->contains($value->value))
                                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            <span>{{ $value->value }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Загрузка фото -->
            <div>
                <label class="block text-sm font-medium text-slate-800 dark:text-slate-800 mb-2">
                    Фотографии (до 10)
                </label>

                <div id="drop-zone"
                    class="border-2 border-dashed border-slate-300 dark:border-navy-500
                    rounded-xl p-6 text-center cursor-pointer hover:border-blue-400 hover:bg-slate-50/50
                    dark:hover:bg-navy-600/50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="mx-auto w-10 h-10 text-slate-400 dark:text-slate-300 mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-9 0v-9m0 0L7.5 10.5M12 7.5l4.5 3" />
                    </svg>
                    <p class="text-sm text-slate-500 dark:text-slate-300">
                        Перетащите файлы или кликните для выбора
                    </p>
                    <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden">
                </div>

                <div id="preview-container" class="mt-4 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3"></div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg shadow-md transition">
                    Сохранить продукт
                </button>
            </div>
        </form>
    </div>

    <style>
        @keyframes slide-in {
            from {
                transform: translateX(20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('photos');
            const dropZone = document.getElementById('drop-zone');
            const previewContainer = document.getElementById('preview-container');

            if (!input || !dropZone || !previewContainer) return;

            dropZone.addEventListener('click', () => input.click());

            dropZone.addEventListener('dragover', e => {
                e.preventDefault();
                dropZone.classList.add('border-blue-400', 'bg-blue-50/30');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-blue-400', 'bg-blue-50/30');
            });

            dropZone.addEventListener('drop', e => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400', 'bg-blue-50/30');
                handleFiles(e.dataTransfer.files);
            });

            input.addEventListener('change', e => handleFiles(e.target.files));

            function handleFiles(files) {
                previewContainer.innerHTML = '';
                const validFiles = Array.from(files).slice(0, 10);

                validFiles.forEach(file => {
                    if (!file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    reader.onload = e => {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="${file.name}"
                                 class="w-full aspect-square object-cover rounded-lg border border-slate-200 dark:border-navy-500 shadow-sm">
                            <button type="button"
                                class="absolute top-1 right-1 bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5 opacity-0 group-hover:opacity-100 transition"
                                title="Удалить">✕</button>
                        `;
                        previewContainer.appendChild(div);

                        div.querySelector('button').addEventListener('click', () => div.remove());
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>

    <script>
        document.getElementById('transpose-btn').onclick = function() {
            const sph = parseFloat(document.querySelector('[name="sph"]').value) || 0;
            const cyl = parseFloat(document.querySelector('[name="cyl"]').value) || 0;
            let axis = parseInt(document.querySelector('[name="axis"]').value) || 0;
            let newSph = +(sph + cyl).toFixed(2);
            let newCyl = +(-cyl).toFixed(2);
            let newAxis = axis <= 90 ? axis + 90 : axis - 90;
            if (newAxis > 180) newAxis -= 180;
            if (newAxis < 1) newAxis += 180;
            document.getElementById('transpose-result').innerText =
                `Sph: ${newSph > 0 ? '+' : ''}${newSph}, Cyl: ${newCyl > 0 ? '+' : ''}${newCyl}, Axis: ${newAxis}°`;
        };
    </script>
@endsection
