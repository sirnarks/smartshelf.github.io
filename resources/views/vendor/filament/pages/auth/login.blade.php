<x-filament::auth-card>
    <div class="text-center mb-6">
        <img src="{{ asset('images/smartshelf-logo.png') }}" alt="SmartShelf Logo" class="h-16 mx-auto mb-4">
        <h1 class="text-3xl font-bold text-primary-600">SmartShelf</h1>
        <p class="text-gray-500">Your Digital Library</p>
    </div>

    <form method="POST" action="{{ route('filament.auth.login') }}" class="space-y-4">
        @csrf

        {{ $this->form }}

        <x-filament::button type="submit" form="login" class="w-full">
            {{ __('filament-panels::pages/auth/login.form.actions.login.label') }}
        </x-filament::button>
    </form>
</x-filament::auth-card>
