<div>
    @if ($submitted)
        <div class="border border-green-500/30 bg-green-500/10 p-6 space-y-2">
            <flux:icon name="check-circle" class="size-8 text-green-500 dark:text-green-400" />
            <p class="font-medium text-green-700 dark:text-green-300">{{ __('contact.success') }}</p>
        </div>
    @else
        @if (config('services.turnstile.site_key'))
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endif

        <form wire:submit="send" class="space-y-6" novalidate>

            {{-- Honeypot --}}
            <div class="hidden" aria-hidden="true">
                <label for="website">Website</label>
                <input type="text" id="website" wire:model="website" autocomplete="off" tabindex="-1">
            </div>

            @if ($errors->has('rate_limit'))
                <div class="border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-600 dark:text-red-400">
                    {{ $errors->first('rate_limit') }}
                </div>
            @endif

            @if ($errors->has('captcha'))
                <div class="border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-600 dark:text-red-400">
                    {{ $errors->first('captcha') }}
                </div>
            @endif

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-semibold uppercase tracking-widest text-text-muted">{{ __('contact.name') }}</label>
                    <input
                        id="name"
                        type="text"
                        wire:model="name"
                        autocomplete="name"
                        required
                        class="w-full border border-border bg-surface-muted px-4 py-3 text-sm text-text placeholder:text-text-muted/40 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-colors"
                        placeholder="{{ __('contact.name') }}"
                    >
                    @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-xs font-semibold uppercase tracking-widest text-text-muted">{{ __('contact.email') }}</label>
                    <input
                        id="email"
                        type="email"
                        wire:model="email"
                        autocomplete="email"
                        required
                        class="w-full border border-border bg-surface-muted px-4 py-3 text-sm text-text placeholder:text-text-muted/40 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-colors"
                        placeholder="{{ __('contact.email') }}"
                    >
                    @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="subject" class="block text-xs font-semibold uppercase tracking-widest text-text-muted">{{ __('contact.subject_optional') }}</label>
                <input
                    id="subject"
                    type="text"
                    wire:model="subject"
                    class="w-full border border-border bg-surface-muted px-4 py-3 text-sm text-text placeholder:text-text-muted/40 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-colors"
                    placeholder="{{ __('contact.subject_optional') }}"
                >
                @error('subject') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="message" class="block text-xs font-semibold uppercase tracking-widest text-text-muted">{{ __('contact.message') }}</label>
                <textarea
                    id="message"
                    wire:model="message"
                    rows="7"
                    required
                    class="w-full border border-border bg-surface-muted px-4 py-3 text-sm text-text placeholder:text-text-muted/40 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-colors resize-y"
                    placeholder="{{ __('contact.message') }}"
                ></textarea>
                @error('message') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Cloudflare Turnstile --}}
            @if (config('services.turnstile.site_key'))
                <div
                    class="cf-turnstile"
                    data-sitekey="{{ config('services.turnstile.site_key') }}"
                    data-callback="onTurnstileSuccess"
                    data-theme="auto"
                ></div>
                <script>
                    function onTurnstileSuccess(token) {
                        @this.set('cfTurnstileResponse', token);
                    }
                </script>
            @endif

            <div class="flex justify-end pt-2">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-3 text-xs font-semibold uppercase tracking-widest bg-accent text-accent-foreground hover:bg-accent-content disabled:opacity-50 transition-colors"
                >
                    <span wire:loading.remove>{{ __('contact.send') }}</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <flux:icon name="arrow-path" class="size-3.5 animate-spin" />
                        {{ __('contact.send') }}
                    </span>
                </button>
            </div>
        </form>
    @endif
</div>
