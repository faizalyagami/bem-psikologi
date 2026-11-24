@props(['title', 'description' => null])

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-heading font-bold text-white">{{ $title }}</h1>
        @if ($description)
            <p class="text-sm text-gray-400 mt-1">{{ $description }}</p>
        @endif
    </div>
    @if (!$slot->isEmpty())
        <div class="flex items-center gap-2">{{ $slot }}</div>
    @endif
</div>
