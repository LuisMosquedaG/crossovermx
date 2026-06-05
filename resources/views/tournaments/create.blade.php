<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Torneo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('tournaments.store') }}">
                        @csrf

                        <!-- Nombre del Torneo -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nombre del Torneo')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Descripción')" />
                            <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" rows="3">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Campo Categoría -->
                        <div class="mb-4">
                            <x-input-label for="category" :value="__('Categoría')" />
                            <select id="category" name="category" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="varonil">Varonil</option>
                                <option value="femenil">Femenil</option>
                                <option value="mixto">Mixto</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="fuerza" :value="__('Fuerza')" />
                            <x-text-input id="fuerza" class="block mt-1 w-full" type="text" name="fuerza" :value="old('fuerza')" required />
                            <x-input-error :messages="$errors->get('fuerza')" class="mt-2" />
                        </div>

                        <!-- Fechas y Ubicación -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <x-input-label for="start_date" :value="__('Fecha de Inicio')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="end_date" :value="__('Fecha de Fin')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="location" :value="__('Ubicación')" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('tournaments.index') }}" class="text-gray-600 underline">Cancelar</a>
                            <x-primary-button class="ml-3">
                                {{ __('Crear Torneo') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>