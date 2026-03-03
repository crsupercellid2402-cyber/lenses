@if($msg->is_from_user)
    <div class="flex">
        <div class="max-w-xs p-3 bg-slate-200 dark:bg-navy-700 rounded-lg">
            @if($msg->photo_url)
                <a href="{{ $msg->photo_url }}" target="_blank" rel="noopener noreferrer">
                    <img src="{{ $msg->photo_url }}" alt="Фото" class="mb-2 rounded-lg max-w-full h-auto">
                </a>
            @endif
            {{ $msg->text }}
            <div class="mt-1 text-xs text-slate-500">
                {{ $msg->created_at->format('H:i') }}
            </div>
        </div>
    </div>
@else
    <div class="flex justify-end">
        <div class="max-w-xs p-3 bg-emerald-600 text-white rounded-lg">
            @if($msg->photo_url)
                <a href="{{ $msg->photo_url }}" target="_blank" rel="noopener noreferrer">
                    <img src="{{ $msg->photo_url }}" alt="Фото" class="mb-2 rounded-lg max-w-full h-auto">
                </a>
            @endif
            {{ $msg->text }}
            <div class="mt-1 text-xs text-emerald-200">
                Менеджер #{{ $msg->admin_id }} — {{ $msg->created_at->format('H:i') }}
            </div>
        </div>
    </div>
@endif
