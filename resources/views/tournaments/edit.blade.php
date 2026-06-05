<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Torneo: ') . $tournament->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- CAMBIO 1: La acción del formulario apunta a la ruta de actualización y envía el ID del torneo -->
                    <form method="POST" action="{{ route('tournaments.update', $tournament) }}">
                        @csrf
                        <!-- CAMBIO 2: Indicamos que este formulario simula un método PUT (para actualizar) -->
                        @method('PUT')

                        <!-- Nombre del Torneo -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nombre del Torneo')" />
                            <!-- CAMBIO 3: Los campos tienen su valor actual en el atributo "value" -->
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ $tournament->name }}" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Descripción')" />
                            <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" rows="3">{{ $tournament->description }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Campo Categoría -->
                        <div class="mb-4">
                            <x-input-label for="category" :value="__('Categoría')" />
                            <select id="category" name="category" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="varonil" {{ $tournament->category === 'varonil' ? 'selected' : '' }}>Varonil</option>
                                <option value="femenil" {{ $tournament->category === 'femenil' ? 'selected' : '' }}>Femenil</option>
                                <option value="mixto" {{ $tournament->category === 'mixto' ? 'selected' : '' }}>Mixto</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="fuerza" :value="__('Fuerza')" />
                            <x-text-input id="fuerza" class="block mt-1 w-full" type="text" name="fuerza" value="{{ $tournament->fuerza }}" required />
                            <x-input-error :messages="$errors->get('fuerza')" class="mt-2" />
                        </div>

                        <!-- Fechas y Ubicación -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <x-input-label for="start_date" :value="__('Fecha de Inicio')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" value="{{ $tournament->start_date->format('Y-m-d') }}" required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="end_date" :value="__('Fecha de Fin')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" value="{{ $tournament->end_date ? $tournament->end_date->format('Y-m-d') : '' }}" />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="location" :value="__('Ubicación')" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" value="{{ $tournament->location }}" />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Checkbox de Activo -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ $tournament->is_active ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Torneo Activo') }}</span>
                            </label>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('tournaments.index') }}" class="text-gray-600 underline">Cancelar</a>
                            <x-primary-button class="ml-3">
                                {{ __('Guardar Cambios') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>