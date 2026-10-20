<section>
    <header class="border-b border-gray-100 pb-4">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Información del Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Actualiza los datos personales de tu cuenta, tu correo electrónico y tu contraseña de acceso.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Datos Principales: Nombre y Correo -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-bold text-gray-700 mb-1">
                    Nombre Completo <span class="text-red-500">*</span>
                </label>
                <input id="name" name="name" type="text"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm"
                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 mb-1">
                    Correo Electrónico <span class="text-red-500">*</span>
                </label>
                <input id="email" name="email" type="email"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm"
                    value="{{ old('email', $user->email) }}" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-xs text-amber-700 bg-amber-50 p-2.5 rounded-lg border border-amber-200">
                            Tu dirección de correo no está verificada.
                            <button form="send-verification" class="underline font-bold text-amber-800 hover:text-amber-900 block mt-1">
                                Haz clic aquí para reenviar el correo de verificación.
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-xs text-green-600 bg-green-50 p-2 rounded border border-green-200">
                                Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Rol y Cliente si aplica -->
        @if($user->role || $user->client)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50/80 p-4 rounded-xl border border-gray-100 text-xs">
                @if($user->role)
                    <div class="flex items-center">
                        <span class="font-bold text-gray-600">Rol asignado:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-orange-100 text-orange-800 ml-2">
                            {{ $user->role->name }}
                        </span>
                    </div>
                @endif
                @if($user->client)
                    <div class="flex items-center">
                        <span class="font-bold text-gray-600">Organización / Cliente:</span>
                        <span class="font-bold text-gray-900 ml-2">{{ $user->client->name }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Sección de Contraseña Integrada -->
        <div class="pt-6 border-t border-gray-200">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Actualizar Contraseña
            </h3>
            <p class="text-xs text-gray-500 mb-4">
                Deja estos campos en blanco si no deseas cambiar tu contraseña actual.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="current_password" class="block text-xs font-bold text-gray-700 mb-1">
                        Contraseña Actual
                    </label>
                    <input id="current_password" name="current_password" type="password"
                        placeholder="••••••••"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm"
                        autocomplete="current-password" />
                    <x-input-error class="mt-2" :messages="$errors->get('current_password')" />
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 mb-1">
                        Nueva Contraseña
                    </label>
                    <input id="password" name="password" type="password"
                        placeholder="Mínimo 8 caracteres"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm"
                        autocomplete="new-password" />
                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-1">
                        Confirmar Contraseña
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        placeholder="Repite la contraseña"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm"
                        autocomplete="new-password" />
                    <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                </div>
            </div>
        </div>

        <!-- Sección de Almacenamiento Ocupado por Imágenes (1 sola barra) -->
        @if(isset($storageInfo))
            <div class="pt-6 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">
                                Espacio de Imágenes y Archivos
                            </h3>
                            <p class="text-xs text-gray-500">
                                {{ $storageInfo['is_tenant'] ? 'Carpeta de tu organización (logos, torneos, equipos, jugadores y canchas)' : 'Espacio total de imágenes en el servidor' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:text-right">
                        <div>
                            <span class="text-sm font-black text-gray-900">{{ $storageInfo['used_formatted'] }}</span>
                            <span class="text-xs text-gray-500 font-medium">/ {{ $storageInfo['limit_formatted'] }}</span>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-black {{ $storageInfo['percentage'] >= 90 ? 'bg-red-100 text-red-700' : ($storageInfo['percentage'] >= 75 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ $storageInfo['percentage'] }}%
                        </span>
                    </div>
                </div>

                <!-- Barra de Progreso Unificada -->
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden p-0.5 border border-gray-200">
                    <div class="h-full rounded-full transition-all duration-500 {{ $storageInfo['percentage'] >= 90 ? 'bg-red-500' : ($storageInfo['percentage'] >= 75 ? 'bg-amber-500' : 'bg-orange-500') }}"
                         style="width: {{ max(1.5, $storageInfo['percentage']) }}%;">
                    </div>
                </div>
            </div>
        @endif

        <!-- Botón Guardar y Notificación de Éxito -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <div>
                @if (session('status') === 'profile-updated')
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3500)"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Cambios guardados exitosamente.
                    </div>
                @endif
            </div>

            <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Guardar Cambios
            </button>
        </div>
    </form>
</section>
