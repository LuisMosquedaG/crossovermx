<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nuevo Usuario</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nombre Completo')" />
                            <!-- CAMBIO: Agregado focus naranja -->
                            <x-text-input id="name" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Correo Electrónico')" />
                            <!-- CAMBIO: Agregado focus naranja -->
                            <x-text-input id="email" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="role_id" :value="__('Rol en el Sistema')" />
                            <!-- CAMBIO: Indigo -> Orange -->
                            <select id="role_id" name="role_id" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Contraseña')" />
                            <!-- CAMBIO: Agregado focus naranja -->
                            <x-text-input id="password" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                            <!-- CAMBIO: Agregado focus naranja -->
                            <x-text-input id="password_confirmation" class="block mt-1 w-full focus:border-orange-500 focus:ring-orange-500" type="password" name="password_confirmation" required autocomplete="new-password" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Crear Usuario') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>