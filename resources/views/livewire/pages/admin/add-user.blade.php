<div>
    @include('livewire.utilities.alerts')
    <x-slot name="header">
        <div class="section-header">
            <h1>Gestión de usuarios</h1>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Añadir Usuario</h4>
        </div>
        <div class="card-body">
            <!-- Name -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <div class="form-group">
                <x-label for="name" :value="__('Nombre')" />
                <x-input id="name" type="text" name="name" :value="old('name')" wire:model='name' />
            </div>

            <!-- Email Address -->
            <div class="form-group">
                <x-label for="email" :value="__('Email')" />
                <x-input id="email" type="email" name="email" :value="old('email')" wire:model='email' />
            </div>

            <!-- Password -->
            <div class="form-group">
                <x-label for="password" :value="__('Contraseña')" />
                <x-input id="password" type="password" name="password" autocomplete="new-password"
                    wire:model='password' />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <x-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" wire:model='password_confirmation' />
            </div>

            <x-button type='submit' wire:click='addUser'>
                {{ __('Registrar') }}
            </x-button>
        </div>
    </div>
</div>
