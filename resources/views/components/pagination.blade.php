@props(['paginator'])

@if ($paginator->hasPages())
    <div class="mt-6 px-4 flex justify-center">
        <nav class="inline-flex space-x-1" role="navigation" aria-label="Pagination">
            {{-- Назад --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 text-gray-400 bg-gray-200 rounded-md cursor-default">&laquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">&laquo;</a>
            @endif

            {{-- Номера страниц --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-1 bg-blue-700 text-white rounded-md font-semibold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                       class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Вперёд --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">&raquo;</a>
            @else
                <span class="px-3 py-1 text-gray-400 bg-gray-200 rounded-md cursor-default">&raquo;</span>
            @endif
        </nav>
    </div>
@endif
