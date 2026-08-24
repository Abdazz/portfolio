<x-layouts.auth>
    <x-auth-header title="Sign in" description="Enter your credentials to access the admin panel." />

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <flux:input
            label="Email"
            type="email"
            name="email"
            :value="old('email')"
            required
            autofocus
            autocomplete="username"
            :invalid="$errors->has('email')"
            :error="$errors->first('email')"
        />

        <flux:input
            label="Password"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            :invalid="$errors->has('password')"
            :error="$errors->first('password')"
            viewable
        />

        <div class="flex items-center justify-between">
            <flux:checkbox name="remember" label="Remember me" />

            @if (Route::has('password.request'))
                <flux:link href="{{ route('password.request') }}" class="text-sm">
                    Forgot password?
                </flux:link>
            @endif
        </div>

        <flux:button type="submit" variant="primary" class="w-full">
            Sign in
        </flux:button>
    </form>
</x-layouts.auth>
