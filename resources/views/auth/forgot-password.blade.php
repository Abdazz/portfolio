<x-layouts.auth>
    <x-auth-header title="Forgot password" description="Enter your email and we'll send you a reset link." />

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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

        <flux:button type="submit" variant="primary" class="w-full">
            Send reset link
        </flux:button>
    </form>

    <p class="text-center text-sm text-text-muted">
        <flux:link href="{{ route('login') }}">Back to login</flux:link>
    </p>
</x-layouts.auth>
