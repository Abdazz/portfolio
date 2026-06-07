<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">{{ __('Resume PDF Cache') }}</x-slot>

        <x-slot name="headerEnd">
            {{ $this->regenerateAction }}
        </x-slot>

        <p class="text-sm text-gray-500 dark:text-gray-400">
            @if ($this->cachedCount() > 0)
                {{ trans_choice('{1} :count PDF cached|[2,*] :count PDFs cached', $this->cachedCount(), ['count' => $this->cachedCount()]) }}
                — {{ __('regenerating will replace them with fresh copies.') }}
            @else
                {{ __('No PDFs cached yet. Click Regenerate to pre-generate them in the background.') }}
            @endif
        </p>
    </x-filament::section>
</x-filament-widgets::widget>
