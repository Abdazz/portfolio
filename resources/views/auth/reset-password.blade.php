<x-layouts.auth>
    <x-auth-header title="Reset password" description="Choose a new password for your account." />

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <flux:input
            label="Email"
            type="email"
            name="email"
            :value="old('email', $request->email)"
            required
            autofocus
            autocomplete="username"
            :invalid="$errors->has('email')"
            :error="$errors->first('email')"
        />

        <flux:input
            label="New password"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            :invalid="$errors->has('password')"
            :error="$errors->first('password')"
            viewable
        />

        <flux:input
            label="Confirm password"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
            :invalid="$errors->has('password_confirmation')"
            :error="$errors->first('password_confirmation')"
            viewable
        />

        <flux:button type="submit" variant="primary" class="w-full">
            Reset password
        </flux:button>
    </form>
</x-layouts.auth>
