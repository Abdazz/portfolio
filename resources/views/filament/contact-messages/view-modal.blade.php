<div class="space-y-4 p-2">
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-gray-500 dark:text-gray-400">{{ __('From') }}</p>
            <p class="font-medium">{{ $message->name }}</p>
        </div>
        <div>
            <p class="text-gray-500 dark:text-gray-400">{{ __('Email') }}</p>
            <p class="font-medium">{{ $message->email }}</p>
        </div>
        @if ($message->subject)
            <div class="col-span-2">
                <p class="text-gray-500 dark:text-gray-400">{{ __('Subject') }}</p>
                <p class="font-medium">{{ $message->subject }}</p>
            </div>
        @endif
        <div>
            <p class="text-gray-500 dark:text-gray-400">{{ __('Received') }}</p>
            <p>{{ $message->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div>
            <p class="text-gray-500 dark:text-gray-400">{{ __('IP address') }}</p>
            <p>{{ $message->ip_address ?? '—' }}</p>
        </div>
    </div>

    <hr class="border-gray-200 dark:border-gray-700">

    <div>
        <p class="mb-1 text-gray-500 dark:text-gray-400 text-sm">{{ __('Message') }}</p>
        <div class="whitespace-pre-wrap rounded-lg bg-gray-50 dark:bg-gray-900 p-4 text-sm">{{ $message->message }}</div>
    </div>
</div>
