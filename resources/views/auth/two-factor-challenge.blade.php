<x-layouts.auth>
    <div x-data="{ recovery: false }">
        <div x-show="!recovery">
            <x-auth-header title="Two-factor authentication" description="Enter the code from your authenticator app." />

            <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
                @csrf

                <flux:input
                    label="Authentication code"
                    type="text"
                    name="code"
                    inputmode="numeric"
                    autofocus
                    autocomplete="one-time-code"
                    :invalid="$errors->has('code')"
                    :error="$errors->first('code')"
                />

                <flux:button type="submit" variant="primary" class="w-full">
                    Confirm
                </flux:button>
            </form>

            <p class="text-center text-sm text-text-muted">
                <flux:link @click.prevent="recovery = true" href="#">Use a recovery code</flux:link>
            </p>
        </div>

        <div x-show="recovery" x-cloak>
            <x-auth-header title="Recovery code" description="Enter one of your emergency recovery codes." />

            <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
                @csrf

                <flux:input
                    label="Recovery code"
                    type="text"
                    name="recovery_code"
                    autocomplete="one-time-code"
                    :invalid="$errors->has('recovery_code')"
                    :error="$errors->first('recovery_code')"
                />

                <flux:button type="submit" variant="primary" class="w-full">
                    Confirm
                </flux:button>
            </form>

            <p class="text-center text-sm text-text-muted">
                <flux:link @click.prevent="recovery = false" href="#">Use authenticator app</flux:link>
            </p>
        </div>
    </div>
</x-layouts.auth>
