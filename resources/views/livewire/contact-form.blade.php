<div>
    @if ($submitted)
        <div class="rounded-2xl border border-green-500/30 bg-green-500/10 p-6 space-y-2">
            <flux:icon name="check-circle" variant="outline" class="size-8 text-green-500 dark:text-green-400" />
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
                <div class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-600 dark:text-red-400">
                    {{ $errors->first('rate_limit') }}
                </div>
            @endif

            @if ($errors->has('captcha'))
                <div class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-600 dark:text-red-400">
                    {{ $errors->first('captcha') }}
                </div>
            @endif

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="space-y-2">
                    <label for="name" class="sr-only">{{ __('contact.name') }}</label>
                    <input
                        id="name"
                        type="text"
                        wire:model="name"
                        autocomplete="name"
                        required
                        class="w-full rounded-lg border border-border bg-surface-muted px-5 py-[14px] leading-none text-text outline-none transition-all duration-300 placeholder:text-[#636363] focus:border-accent focus:outline-none dark:border-[#22272c] dark:bg-surface-deep"
                        placeholder="{{ __('contact.name') }}"
                    >
                    @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="sr-only">{{ __('contact.email') }}</label>
                    <input
                        id="email"
                        type="email"
                        wire:model="email"
                        autocomplete="email"
                        required
                        class="w-full rounded-lg border border-border bg-surface-muted px-5 py-[14px] leading-none text-text outline-none transition-all duration-300 placeholder:text-[#636363] focus:border-accent focus:outline-none dark:border-[#22272c] dark:bg-surface-deep"
                        placeholder="{{ __('contact.email') }}"
                    >
                    @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="subject" class="sr-only">{{ __('contact.subject_optional') }}</label>
                <input
                    id="subject"
                    type="text"
                    wire:model="subject"
                    class="w-full rounded-lg border border-border bg-surface-muted px-5 py-[14px] leading-none text-text outline-none transition-all duration-300 placeholder:text-[#636363] focus:border-accent focus:outline-none dark:border-[#22272c] dark:bg-surface-deep"
                    placeholder="{{ __('contact.subject_optional') }}"
                >
                @error('subject') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="message" class="sr-only">{{ __('contact.message') }}</label>
                <textarea
                    id="message"
                    wire:model="message"
                    rows="7"
                    required
                    class="w-full rounded-lg border border-border bg-surface-muted px-5 py-[14px] leading-none text-text outline-none transition-all duration-300 placeholder:text-[#636363] focus:border-accent focus:outline-none dark:border-[#22272c] dark:bg-surface-deep resize-y"
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

            <div class="flex justify-start pt-2">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="group inline-flex items-center justify-center gap-2.5 rounded-full gradient-secondary px-[35px] py-[17px] text-[15px] font-bold capitalize leading-none text-white transition-all duration-300 hover:[background-position:-100%_0] disabled:pointer-events-none disabled:opacity-60"
                >
                    <span wire:loading.remove class="inline-flex items-center gap-2.5">
                        {{ __('contact.send') }}
                        <flux:icon name="arrow-up-right" variant="outline" class="size-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                    </span>
                    <span wire:loading class="inline-flex items-center gap-2.5">
                        <flux:icon name="arrow-path" variant="outline" class="size-4 animate-spin" />
                        {{ __('contact.send') }}
                    </span>
                </button>
            </div>
        </form>
    @endif
</div>
