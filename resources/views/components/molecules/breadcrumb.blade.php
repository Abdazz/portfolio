@props(['items' => []])

{{-- $items: array of ['label' => string, 'href' => string|null] --}}
<nav aria-label="{{ __('Breadcrumb') }}">
    <ol class="flex items-center gap-1 text-sm text-text-muted">
        @foreach ($items as $i => $item)
            <li class="flex items-center gap-1">
                @if ($i > 0)
                    <flux:icon name="chevron-right" class="size-3 text-text-muted/50" />
                @endif
                @if (isset($item['href']) && $i < count($items) - 1)
                    <a href="{{ $item['href'] }}" class="hover:text-text transition-colors">{{ $item['label'] }}</a>
                @else
                    <span class="text-text" aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
