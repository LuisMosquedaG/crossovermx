<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <!-- Carga de librería externa (Movida fuera del tbody para validación HTML correcta y carga temprana) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <!-- Quill Rich Text Editor CDN -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <div class="py-12">
        <div class="w-[96%] md:w-[90%] mx-auto mb-[10vh]">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    <!-- Contenedor Flex: Botón y Formulario de Búsqueda y Filtros Unificados (Idéntico a Equipos) -->
                    <div class="mb-6 flex flex-col xl:flex-row items-center gap-3">
                        
                        <!-- 1. El Botón Crear -->
                        <?php if(!auth()->user()->hasRole('Arbitro')): ?>
                            <button onclick="openCreateModal()" class="w-full xl:w-auto shrink-0 bg-orange-600 text-white font-bold py-2 px-4 rounded hover:bg-orange-700 transition duration-150 ease-in-out">
                                Crear Nuevo Torneo
                            </button>
                        <?php endif; ?>

                        <!-- 2. FORMULARIO UNIFICADO DE BÚSQUEDA Y FILTROS -->
                        <form action="<?php echo e(route('tournaments.index')); ?>" method="GET" class="w-full xl:flex-1 flex flex-col md:flex-row flex-wrap xl:flex-nowrap gap-2.5 items-center">
                            
                            <!-- Buscador de Texto -->
                            <div class="relative w-full md:flex-1 min-w-[200px]">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" 
                                    placeholder="Buscar por nombre, ubicación...">
                            </div>

                            <!-- Filtro: Tipo de Torneo -->
                            <div class="w-full md:w-auto">
                                <select name="tournament_type" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 border py-2 px-3 bg-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                    <option value="">Todos los Tipos</option>
                                    <option value="round_robin" <?php echo e(request('tournament_type') == 'round_robin' ? 'selected' : ''); ?>>Liga</option>
                                    <option value="elimination" <?php echo e(request('tournament_type') == 'elimination' ? 'selected' : ''); ?>>Eliminatoria Directa</option>
                                    <option value="double_elimination" <?php echo e(request('tournament_type') == 'double_elimination' ? 'selected' : ''); ?>>Doble Eliminatoria</option>
                                </select>
                            </div>

                            <!-- Filtro: Categoría -->
                            <div class="w-full md:w-auto">
                                <select name="category" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 border py-2 px-3 bg-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                    <option value="">Todas las Categorías</option>
                                    <option value="Varonil" <?php echo e(request('category') == 'Varonil' ? 'selected' : ''); ?>>Varonil</option>
                                    <option value="Femenil" <?php echo e(request('category') == 'Femenil' ? 'selected' : ''); ?>>Femenil</option>
                                    <option value="Mixto" <?php echo e(request('category') == 'Mixto' ? 'selected' : ''); ?>>Mixto</option>
                                    <option value="Infantil" <?php echo e(request('category') == 'Infantil' ? 'selected' : ''); ?>>Infantil</option>
                                    <option value="Varios" <?php echo e(request('category') == 'Varios' ? 'selected' : ''); ?>>Varios</option>
                                </select>
                            </div>

                            <!-- Filtro: Fuerza -->
                            <div class="w-full md:w-auto">
                                <select name="fuerza" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 border py-2 px-3 bg-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                    <option value="">Todas las Fuerzas</option>
                                    <?php $__currentLoopData = $strengths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $str): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($str->name); ?>" <?php echo e(request('fuerza') == $str->name ? 'selected' : ''); ?>>
                                            <?php echo e($str->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Filtro: Estado -->
                            <div class="w-full md:w-auto">
                                <select name="status" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 border py-2 px-3 bg-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                    <option value="">Todos los Estados</option>
                                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pendiente</option>
                                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Activo</option>
                                    <option value="finished" <?php echo e(request('status') == 'finished' ? 'selected' : ''); ?>>Finalizado</option>
                                </select>
                            </div>

                            <!-- Filtro: Fecha Inicio -->
                            <div class="w-full md:w-auto flex items-center gap-1">
                                <span class="text-xs text-gray-500 font-medium whitespace-nowrap">Desde:</span>
                                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" onchange="this.form.submit()" class="block rounded-md border-gray-300 border py-1.5 px-2 bg-white shadow-sm focus:border-orange-500 focus:ring-orange-500 text-xs">
                            </div>

                            <!-- Filtro: Fecha Fin -->
                            <div class="w-full md:w-auto flex items-center gap-1">
                                <span class="text-xs text-gray-500 font-medium whitespace-nowrap">Hasta:</span>
                                <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" onchange="this.form.submit()" class="block rounded-md border-gray-300 border py-1.5 px-2 bg-white shadow-sm focus:border-orange-500 focus:ring-orange-500 text-xs">
                            </div>

                        </form>

                    </div>

                    <!-- Mensaje de Éxito -->
                    <?php if(session('message')): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo e(session('message')); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Mensaje de Error (NUEVO) -->
                    <?php if(session('error')): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- INICIO CAMBIO: Envolver tabla para Responsive -->
                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <!-- CAMBIO: Columna Acciones movida al inicio -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Torneo</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fuerza</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Inicio</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Fin</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $tournaments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tournament): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <!-- CAMBIO: Columna Acciones movida al inicio -->
                                        <!-- Para la celda de acciones, centramos el contenedor flex -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex items-center justify-center space-x-3">

                                                <!-- NUEVO BOTÓN VER EQUIPOS REGISTRADOS -->
                                                <button onclick="showTeamsListModal(<?php echo e($tournament->id); ?>, '<?php echo e(addslashes($tournament->name)); ?>', '<?php echo e($tournament->status); ?>')" class="text-orange-600 hover:text-orange-900" title="Ver Equipos Registrados">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                                    </svg>
                                                </button>

                                                <!-- NUEVO BOTÓN REGLAMENTO -->
                                                <button onclick="openRulesModal(<?php echo e($tournament->id); ?>, '<?php echo e(addslashes($tournament->name)); ?>')" class="text-gray-600 hover:text-gray-900" title="Ver Reglamento">
                                                    <!-- Icono de Documento/Libro -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                                    </svg>
                                                </button>
                                                
                                                <!-- 1. Botón de Editar (Solo Admin y SOLO si está Pendiente) -->
                                                <?php if(!auth()->user()->hasRole('Arbitro') && !auth()->user()->hasRole('Coach') && $tournament->status === 'pending'): ?>
                                                    <button onclick="openEditModal(<?php echo e($tournament->toJson()); ?>)" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </button>
                                                <?php endif; ?>

                                                <!-- 2. Botón de Calendario (Solo Admin) -->
                                                <?php if(!auth()->user()->hasRole('Arbitro') && !auth()->user()->hasRole('Coach')): ?>
                                                    <?php if($tournament->games()->exists()): ?>
                                                        <button onclick="openViewCalendarModal(<?php echo e($tournament->id); ?>, '<?php echo e($tournament->status); ?>')" class="text-blue-600 hover:text-blue-900" title="Ver Configuración del Calendario">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                            </svg>
                                                        </button>
                                                    <?php else: ?>
                                                        <button onclick="openCreateCalendarModal(<?php echo e($tournament->toJson()); ?>)" class="text-green-600 hover:text-green-900" title="Generar Calendario">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                                            </svg>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <!-- 3. Botón Tabla de Posiciones (Solo si hay calendario) -->
                                                <?php if($tournament->games()->exists()): ?>
                                                    <a href="<?php echo e(route('tournaments.standings', $tournament)); ?>" class="text-emerald-600 hover:text-emerald-900" title="Tabla de Posiciones">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125z" />
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>

                                                <!-- 4. Botón Ver Partidos (Visible para todos, si hay calendario) -->
                                                <?php if($tournament->games()->exists()): ?>
                                                    <a href="<?php echo e(route('tournaments.schedule', $tournament->id)); ?>" class="text-purple-600 hover:text-purple-900" title="Ver Partidos">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>

                                                <!-- 5. Botón Eliminar Torneo (Solo Admin) -->
                                                <?php if(!auth()->user()->hasRole('Arbitro') && !auth()->user()->hasRole('Coach')): ?>
                                                    <form action="<?php echo e(route('tournaments.destroy', $tournament)); ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este torneo?');" class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar Torneo">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                            </div>
                                        </td>

                                        <!-- CELDA NOMBRE -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">
                                            <div class="flex items-center justify-center gap-3">
                                                <?php if($tournament->logo_path): ?>
                                                    <img src="<?php echo e(asset('storage/' . $tournament->logo_path)); ?>" alt="<?php echo e($tournament->name); ?>" class="w-8 h-8 rounded-full object-cover border border-gray-200 shrink-0">
                                                <?php else: ?>
                                                    <div class="w-8 h-8 rounded-full bg-orange-100 border border-orange-200 flex items-center justify-center text-orange-700 font-bold text-xs shrink-0">
                                                        <?php echo e(strtoupper(substr($tournament->name, 0, 1))); ?>

                                                    </div>
                                                <?php endif; ?>
                                                <span><?php echo e($tournament->name); ?></span>
                                            </div>
                                        </td>
                                        
                                        <!-- CELDA TIPO DE TORNEO (Estilo Pastilla idéntico a Configuración de Calendario) -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <?php
                                                $tSettings = $tournament->settings ? $tournament->settings->settings : [];
                                                $tType = $tSettings['tournament_type'] ?? 'round_robin';
                                                $typeLabels = [
                                                    'round_robin' => 'Liga (Todos contra todos + Playoffs)',
                                                    'elimination' => 'Eliminatoria Directa',
                                                    'single_elimination' => 'Eliminatoria Directa',
                                                    'double_elimination' => 'Doble Eliminatoria',
                                                    'groups' => 'Fase de Grupos',
                                                    'groups_and_playoffs' => 'Grupos y Liguilla',
                                                ];
                                                $labelText = $typeLabels[$tType] ?? ucfirst(str_replace('_', ' ', $tType));
                                            ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">
                                                <?php echo e($labelText); ?>

                                            </span>
                                        </td>
                                        
                                        <!-- ... dentro de tu tabla tbody ... -->

                                        <!-- CELDA CATEGORÍA (Corrección de Mayúsculas/Minúsculas) -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <?php if(is_null($tournament->category)): ?>
                                                <!-- Si está vacío (sin equipos), muestra guion -->
                                                <span class="text-gray-400 font-medium">-</span>
                                            <?php elseif($tournament->category == 'Varonil'): ?> <!-- CAMBIO AQUÍ: 'Varonil' -->
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Varonil</span>
                                            <?php elseif($tournament->category == 'Femenil'): ?> <!-- CAMBIO AQUÍ: 'Femenil' -->
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">Femenil</span>
                                            <?php elseif($tournament->category == 'Mixto'): ?> <!-- CAMBIO AQUÍ: 'Mixto' -->
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Mixto</span>
                                            <?php elseif($tournament->category == 'Varios'): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-800">Varios</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><?php echo e($tournament->category); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- CELDA FUERZA (MODIFICADA: Estilo de pastilla gris SOLO si hay dato) -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            <?php if(!empty($tournament->fuerza)): ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                    <?php echo e($tournament->fuerza); ?>

                                                </span>
                                            <?php else: ?>
                                                <span>-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500"><?php echo e($tournament->start_date->format('d-m-Y')); ?></td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            <?php if($tournament->end_date): ?>
                                                <?php echo e($tournament->end_date->format('d-m-Y')); ?>

                                            <?php else: ?>
                                                <span class="text-gray-400">No definida</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <?php if($tournament->status == 'pending'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                            <?php elseif($tournament->status == 'active'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                            <?php elseif($tournament->status == 'finished'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">Terminado</span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"><?php echo e($tournament->status); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <!-- Colspan corregido a 8 -->
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No hay torneos registrados.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- FIN CAMBIO -->
                    
                    <!-- CONTROLES DE PAGINACIÓN -->
                    <div class="mt-4">
                        <?php echo e($tournaments->links()); ?>

                    </div>
                    <!-- FIN CONTROLES DE PAGINACIÓN -->

                </div>
            </div>
        </div>
    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

    <!-- Modal para Crear/Editar Torneo -->
    <div id="tournamentModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="tournamentForm" onsubmit="submitForm(event)" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="modalTitle">Crear Nuevo Torneo</h3>
                                    <div class="mb-4">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_name','value' => __('Nombre del Torneo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre del Torneo'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_name','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'name','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_name','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'name','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('name'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('name')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                                    </div>
                                    <div class="mb-4">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_description','value' => __('Descripción')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_description','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Descripción'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                        <textarea id="modal_description" name="description" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full" rows="3"></textarea>
                                    </div>
                                    
                                    <!-- LOGO DEL TORNEO -->
                                    <div class="mb-4">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_logo','value' => __('Logo del Torneo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_logo','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Logo del Torneo'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                        <div class="flex items-center gap-4 mt-2 bg-slate-50 p-3 rounded-xl border border-gray-200">
                                            <div class="shrink-0 relative">
                                                <img id="logo_preview" src="" alt="Vista previa" class="w-16 h-16 rounded-2xl object-cover border border-gray-200 hidden bg-white">
                                                <div id="logo_preview_placeholder" class="w-16 h-16 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-xs font-semibold bg-white">
                                                    Sin Logo
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <input type="file" id="modal_logo" name="logo" accept="image/*" onchange="previewTournamentLogo(this)" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition duration-150 cursor-pointer">
                                                <p class="text-[10px] text-gray-400 mt-1">Formatos permitidos: PNG, JPG, JPEG, GIF. Máximo 2MB.</p>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div><?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_start_date','value' => __('Fecha de Inicio')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_start_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Fecha de Inicio'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_start_date','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'date','name' => 'start_date','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_start_date','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'date','name' => 'start_date','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?></div>
                                        <div><?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_end_date','value' => __('Fecha de Fin')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_end_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Fecha de Fin'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_end_date','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'date','name' => 'end_date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_end_date','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'date','name' => 'end_date']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?></div>
                                        <div><?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_location','value' => __('Ubicación')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_location','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Ubicación'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?><?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_location','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'location']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_location','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'location']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['type' => 'submit','id' => 'saveButton','class' => 'w-full sm:ml-3 sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','id' => 'saveButton','class' => 'w-full sm:ml-3 sm:w-auto']); ?>Guardar <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                            <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Configuración de Calendario (ahora dinámico) -->
    <div id="calendarModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="calendarForm" onsubmit="handleCalendarSubmit(event)">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="tournament_id" id="calendar_tournament_id">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="calendarModalTitle"></h3>
                                    <div id="calendarFormContent">
                                        <!-- El contenido se inyectará aquí con JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="calendarModalActions" class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <!-- Los botones se llenarán con JavaScript -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Éxito (sin cambios) -->
    <div id="successModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <div class="bg-white p-6 text-center">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <p class="text-sm text-gray-700" id="successModalMessage"></p>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 text-center">
                        <button type="button" onclick="handleSuccessRedirect()" class="inline-flex justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Reglamento -->
    <div id="rulesModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-4xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="rulesForm" onsubmit="saveRules(event)">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="tournament_id" id="rules_tournament_id">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="rulesModalTitle">Reglamento del Torneo</h3>
                                    
                                    <div class="mb-4">
                                        <!-- Quill Editor Container -->
                                        <div id="rules_editor_wrapper" class="border border-gray-300 rounded-md overflow-hidden">
                                            <div id="rules_editor" class="h-[480px] bg-white text-slate-800" style="font-size: 14px;"></div>
                                        </div>
                                        <!-- Hidden textarea to store the HTML content -->
                                        <textarea id="rules_content" name="reglamento" class="hidden"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <!-- Botón Guardar (Solo visible para Admin vía JS) -->
                            <button type="submit" id="saveRulesButton" class="hidden inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-700 transition duration-150 ease-in-out sm:ml-3 sm:w-auto">Guardar Cambios</button>
                            <!-- NUEVO BOTÓN CERRAR (Visible para todos) --> 
                            <button type="button" onclick="closeRulesModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cerrar</button>
                            <!-- NUEVO BOTÓN DESCARGAR PDF (Visible para todos) -->
                            <button type="button" onclick="downloadPDF()" class="mr-2 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition duration-150 ease-in-out sm:ml-3 sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Descargar PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Lista de Equipos del Torneo -->
    <div id="teamsListModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-5xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="teamsListModalTitle">
                                    Equipos del Torneo
                                </h3>
                                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fuerza</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estatus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="teamsTableBody" class="bg-white divide-y divide-gray-200">
                                        </tbody>
                                    </table>
                                    <div id="noTeamsMessage" class="hidden text-center py-4 text-gray-500 font-medium">
                                        Este torneo aún no tiene equipos registrados.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <?php if(!auth()->user()->hasRole('Arbitro') && !auth()->user()->hasRole('Coach')): ?>
                            <button type="button" id="btnCreateTeamFromList" onclick="openCreateTeamModalFromList()" class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm transition duration-150 ease-in-out sm:ml-3">
                                Crear Nuevo Equipo
                            </button>
                        <?php endif; ?>
                        <button type="button" onclick="closeTeamsListModal()" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Estadísticas Acumuladas del Equipo -->
    <div id="teamStatsModal" class="fixed inset-0 hidden" style="z-index: 60;">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-4xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4 text-center" id="teamStatsModalTitle">
                                    Estadísticas Acumuladas
                                </h3>
                                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                    <table class="min-w-full text-sm text-left">
                                        <thead class="bg-gray-100 text-gray-600">
                                            <tr>
                                                <th class="px-3 py-2">Jugador</th>
                                                <th class="px-3 py-2 text-center">1 Pt</th>
                                                <th class="px-3 py-2 text-center">2 Pt</th>
                                                <th class="px-3 py-2 text-center">3 Pt</th>
                                                <th class="px-3 py-2 text-center font-bold bg-gray-200">Total</th>
                                                <th class="px-3 py-2 text-center">Faltas</th>
                                            </tr>
                                        </thead>
                                        <tbody id="teamStatsTableBody" class="divide-y divide-gray-200">
                                        </tbody>
                                    </table>
                                    <div id="noStatsMessage" class="hidden text-center py-4 text-gray-500 bg-white">
                                        Este equipo aún no tiene estadísticas registradas.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" onclick="closeTeamStatsModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Equipo -->
    <div id="teamModal" class="fixed inset-0 hidden" style="z-index: 60;">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="teamForm" onsubmit="submitTeamForm(event)" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="_method" id="team_form_method" value="POST">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="teamModalTitle">
                                        Editar Equipo
                                    </h3>
                                    <div class="mb-4">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'team_modal_name','value' => __('Nombre del Equipo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'team_modal_name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre del Equipo'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'team_modal_name','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'name','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'team_modal_name','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'name','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'team_modal_coach_id','value' => __('Entrenador Asignado')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'team_modal_coach_id','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Entrenador Asignado'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                        <div class="flex mt-1">
                                            <select id="team_modal_coach_id" name="coach_id" 
                                                class="flex-1 border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-l-md shadow-sm block w-full border py-2 px-3 bg-white">
                                                <option value="">-- Sin Entrenador --</option>
                                                <?php $__currentLoopData = $coaches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coach): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($coach->id); ?>"><?php echo e($coach->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <button type="button" onclick="openQuickCoachModal()" 
                                                class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-md border border-l-0 border-orange-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                                                title="Crear nuevo entrenador">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'team_modal_tournament_id','value' => __('Torneo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'team_modal_tournament_id','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Torneo'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                        <select id="team_modal_tournament_id" name="tournament_id" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                            <option value="">-- Sin torneo (Opcional) --</option>
                                            <?php $__currentLoopData = $tournaments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($t->status === 'pending'): ?>
                                                    <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'team_modal_status','value' => __('Estatus del Equipo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'team_modal_status','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Estatus del Equipo'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                        <select id="team_modal_status" name="status" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                            <option value="pending">Pendiente (Necesita firma)</option>
                                            <option value="active">Activo</option>
                                            <option value="suspended">Suspendido</option>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'team_modal_category','value' => __('Categoría')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'team_modal_category','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Categoría'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                            <select id="team_modal_category" name="category" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                                <option value="">-- Seleccionar --</option>
                                                <option value="Varonil">Varonil</option>
                                                <option value="Femenil">Femenil</option>
                                                <option value="Mixto">Mixto</option>
                                                <option value="Infantil">Infantil</option>
                                            </select>
                                        </div>
                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'team_modal_strength','value' => __('Fuerza / Nivel')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'team_modal_strength','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Fuerza / Nivel'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                            <div class="flex mt-1">
                                                <select id="team_modal_strength" name="strength" class="flex-1 border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-l-md shadow-sm block w-full border py-2 px-3 bg-white">
                                                    <option value="">-- Seleccionar --</option>
                                                    <?php $__currentLoopData = $strengths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $str): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($str->name); ?>"><?php echo e($str->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <button type="button" onclick="openAddStrengthModal()" 
                                                    class="bg-orange-600 hover:bg-orange-700 text-white rounded-r-md border border-l-0 border-orange-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                                                    title="Agregar nueva fuerza">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'team_modal_image','value' => __('Logo del Equipo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'team_modal_image','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Logo del Equipo'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'team_modal_image','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'file','name' => 'image']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'team_modal_image','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'file','name' => 'image']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['type' => 'submit','id' => 'teamSaveButton','class' => 'w-full sm:ml-3 sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','id' => 'teamSaveButton','class' => 'w-full sm:ml-3 sm:w-auto']); ?>
                                Guardar
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                            <button type="button" onclick="closeTeamModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Coach Modal (Creación rápida de Entrenador) -->
    <div id="quickCoachModal" class="fixed inset-0 hidden" style="z-index: 70;">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="quickCoachForm" onsubmit="submitQuickCoachForm(event)">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="role" value="Coach">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 1-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 5.472m0 0a9.09 9.09 0 0 1-3.74-.479 3 3 0 0 0 4.682-2.72m.94 3.198c.083-.89.142-1.79.18-2.692M14.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">Nuevo Entrenador</h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="q_name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                            <input type="text" name="name" id="q_name" required placeholder="Ej. Juan Pérez" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                        </div>
                                        <div>
                                            <label for="q_email" class="block text-sm font-medium text-gray-700">Usuario</label>
                                            <input type="text" name="email" id="q_email" required placeholder="Ej. juan.perez" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                            <p class="text-xs text-gray-500 mt-1">Solo el usuario (sin @). El dominio del cliente se agregará automáticamente.</p>
                                        </div>
                                        <div>
                                            <label for="q_password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                                            <input type="password" name="password" id="q_password" required 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                        </div>
                                        <div>
                                            <label for="q_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                                            <input type="password" name="password_confirmation" id="q_password_confirmation" required 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 sm:ml-3 sm:w-auto">
                                Crear y Asignar
                            </button>
                            <button type="button" onclick="closeQuickCoachModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Strength Modal (Creación rápida de Fuerza) -->
    <div id="addStrengthModal" class="fixed inset-0 hidden" style="z-index: 70;">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-sm transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="addStrengthForm" onsubmit="submitStrengthForm(event)">
                        <?php echo csrf_field(); ?>
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">Nueva Fuerza</h3>
                                    <div class="mt-2">
                                        <label for="new_strength_name" class="block text-sm font-medium text-gray-700">Nombre de la Fuerza</label>
                                        <input type="text" name="name" id="new_strength_name" required placeholder="Ej. 2013-2014" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 sm:ml-3 sm:w-auto">
                                Agregar
                            </button>
                            <button type="button" onclick="closeAddStrengthModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Lista de Jugadores de un Equipo -->
    <div id="playersListModal" class="fixed inset-0 hidden" style="z-index: 70;">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-5xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="playersListModalTitle">
                                    Jugadores del Equipo
                                </h3>
                                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">RFC</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Posición</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estatus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="playersTableBody" class="bg-white divide-y divide-gray-200">
                                        </tbody>
                                    </table>
                                    <div id="noPlayersMessage" class="hidden text-center py-4 text-gray-500">
                                        Este equipo aún no tiene jugadores.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <?php if(!auth()->user()->hasRole('Arbitro')): ?>
                            <button type="button" id="btnCreatePlayerFromList" onclick="openCreatePlayerModalFromList()" class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm transition duration-150 ease-in-out sm:ml-3">
                                Nuevo Jugador
                            </button>
                        <?php endif; ?>
                        <button type="button" onclick="closePlayersListModal()" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Crear Nuevo Jugador (Rápido) -->
    <div id="playerQuickModal" class="fixed inset-0 hidden" style="z-index: 80;">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="playerQuickForm" onsubmit="submitPlayerQuickForm(event)">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="team_id" id="player_quick_team_id">
                        <input type="hidden" name="status" value="active">
                        <input type="hidden" name="gender" id="player_quick_gender" value="">
                        
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="playerQuickModalTitle">
                                        Nuevo Jugador
                                    </h3>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'player_quick_name','value' => __('Nombre Completo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'player_quick_name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre Completo'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'player_quick_name','name' => 'name','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'player_quick_name','name' => 'name','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                        </div>
                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'player_quick_number','value' => __('Número de Camiseta')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'player_quick_number','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Número de Camiseta'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'player_quick_number','name' => 'number','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'number','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'player_quick_number','name' => 'number','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'number','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                        </div>
                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'player_quick_image','value' => __('Fotografía (Opcional)')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'player_quick_image','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Fotografía (Opcional)'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'player_quick_image','name' => 'image','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500 border border-gray-300 rounded-md p-1 bg-white text-sm','type' => 'file']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'player_quick_image','name' => 'image','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500 border border-gray-300 rounded-md p-1 bg-white text-sm','type' => 'file']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['type' => 'submit','id' => 'playerQuickSaveButton','class' => 'w-full sm:ml-3 sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','id' => 'playerQuickSaveButton','class' => 'w-full sm:ml-3 sm:w-auto']); ?>
                                Guardar
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                            <button type="button" onclick="closePlayerQuickModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    const allCourts = <?php echo json_encode($courts, 15, 512) ?>;
    function openCreateModal() {
        resetForm();
        document.getElementById('modalTitle').innerText = 'Crear Nuevo Torneo';
        document.getElementById('form_method').value = 'POST';
        document.getElementById('tournamentForm').action = '<?php echo e(route("tournaments.store")); ?>';
        
        const preview = document.getElementById('logo_preview');
        const placeholder = document.getElementById('logo_preview_placeholder');
        preview.src = '';
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');

        const saveButton = document.getElementById('saveButton');
        saveButton.className = 'inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto';    
        document.getElementById('tournamentModal').classList.remove('hidden');
    }
    function openEditModal(tournament) {
        resetForm();
        document.getElementById('modalTitle').innerText = 'Editar Torneo: ' + tournament.name;
        document.getElementById('form_method').value = 'PUT';
        document.getElementById('tournamentForm').action = '<?php echo e(route("tournaments.update", ":id")); ?>'.replace(':id', tournament.id);
        const saveButton = document.getElementById('saveButton');
        saveButton.className = 'inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto';
        document.getElementById('modal_name').value = tournament.name;
        document.getElementById('modal_description').value = tournament.description || '';
        if (tournament.start_date) document.getElementById('modal_start_date').value = tournament.start_date.substring(0, 10);
        if (tournament.end_date) document.getElementById('modal_end_date').value = tournament.end_date.substring(0, 10);
        document.getElementById('modal_location').value = tournament.location || '';
        
        const preview = document.getElementById('logo_preview');
        const placeholder = document.getElementById('logo_preview_placeholder');
        if (tournament.logo_path) {
            preview.src = `/storage/${tournament.logo_path}`;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
        
        document.getElementById('tournamentModal').classList.remove('hidden');
    }
    function closeModal() { document.getElementById('tournamentModal').classList.add('hidden'); }
    function resetForm() { 
        document.getElementById('tournamentForm').reset(); 
        const preview = document.getElementById('logo_preview');
        const placeholder = document.getElementById('logo_preview_placeholder');
        if (preview) {
            preview.src = '';
            preview.classList.add('hidden');
        }
        if (placeholder) {
            placeholder.classList.remove('hidden');
        }
    }
    function previewTournamentLogo(input) {
        const preview = document.getElementById('logo_preview');
        const placeholder = document.getElementById('logo_preview_placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }
    async function submitForm(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    // --- CORRECCIÓN AQUÍ: Obtenemos el token directamente de las etiquetas meta ---
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // --------------------------------------------------------------------------

    try {
        const response = await fetch(form.action, {
            method: 'POST', 
            body: formData, 
            headers: { 
                'Accept': 'application/json', 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken // <--- Agregamos esto explícitamente
            } 
        });
        
        // Si la respuesta es exitosa (200 o 300), recargamos para ver cambios
        if (response.ok) { 
            window.location.reload(); 
        } else { 
            const errorData = await response.json().catch(() => ({}));
            let errorMessage = 'Ocurrió un error al guardar.';
            if (errorData.errors) {
                errorMessage = Object.values(errorData.errors).flat().join('\n');
            } else if (errorData.message) {
                errorMessage = errorData.message;
            }
            alert(errorMessage);
            console.error('Error al guardar:', errorData);
        }
    } catch (error) { 
        console.error('Error al enviar el formulario:', error); 
        alert('Ocurrió un error inesperado.'); 
    }
}

    function openCreateCalendarModal(tournament) {
        resetCalendarModal();
        document.getElementById('calendarModalTitle').innerText = `Generar Calendario: ${tournament.name}`;
        document.getElementById('calendar_tournament_id').value = tournament.id;
        
        // CAMBIO: Aumentamos el ancho del modal a max-w-4xl para mejor visibilidad
        const modalPanel = document.querySelector('#calendarModal .transform');
        modalPanel.classList.remove('max-w-2xl');
        modalPanel.classList.add('max-w-4xl');

        populateCreateForm();
        setCreateActions();
        document.getElementById('calendarModal').classList.remove('hidden');
    }

    async function openViewCalendarModal(tournamentId, status) {
        resetCalendarModal();
        document.getElementById('calendar_tournament_id').value = tournamentId;
        try {
            const response = await fetch(`/tournaments/${tournamentId}/settings`);
            if (!response.ok) throw new Error('No se pudo cargar la configuración.');
            const settings = await response.json();
            document.getElementById('calendarModalTitle').innerText = 'Configuración del Calendario';
            populateViewForm(settings);
            setViewActions(tournamentId, status);
            document.getElementById('calendarModal').classList.remove('hidden');
        } catch (error) { alert(error.message); console.error(error); }
    }

    function resetCalendarModal() {
        document.getElementById('calendarForm').reset();
        document.getElementById('calendarFormContent').innerHTML = '';
        document.getElementById('calendarModalActions').innerHTML = '';
    }

    function populateCreateForm() {
        const courtsList = allCourts || [];

        // Clases de estilo
        // CAMBIO: Eliminado 'mt-2' de labelClass para evitar alineaciones inconsistentes
        const inputClass = "block w-full rounded-md border-gray-300 border py-2 px-3 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm";
        const labelClass = "block text-sm font-medium text-gray-700 mb-1"; 
        
        const checkboxClass = "h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-0 focus:outline-none";

        const courtCheckboxes = courtsList.map(court => `
            <div class="flex items-center mb-2">
                <input type="checkbox" name="courts[]" value="${court.id}" id="court_${court.id}" class="${checkboxClass}">
                <label for="court_${court.id}" class="ml-2 block text-sm text-gray-900">${court.name}</label>
            </div>
        `).join('');

        const renderDay = (val, label) => `
            <label class="flex items-center">
                <input type="checkbox" name="days[]" value="${val}" class="${checkboxClass}">
                <span class="ml-2 text-sm text-gray-700">${label.substring(0, 3)}</span>
            </label>
        `;

        const content = `
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- COLUMNA 1 -->
                <div class="space-y-4"> <!-- Aumentado a space-y-4 para separar grupos -->
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide border-b pb-1">General</h3>
                    
                    <div>
                        <label class="${labelClass}">Formato de Competencia</label>
                        <select name="tournament_type" class="${inputClass}">
                            <option value="round_robin">Todos contra todos + Playoffs</option>
                            <option value="elimination">Eliminatoria Directa</option>
                            <option value="double_elimination">Doble Eliminatoria</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="${labelClass}">Hora Inicio</label>
                            <input type="time" name="start_time" class="${inputClass}">
                        </div>
                        <div>
                            <label class="${labelClass}">Hora Fin</label>
                            <input type="time" name="end_time" class="${inputClass}">
                        </div>
                    </div>

                    <div>
                        <label class="${labelClass}">Reglas de Descanso</label>
                        <div class="space-y-2 bg-gray-50 p-3 rounded border border-gray-200">
                            <label class="flex items-center">
                                <input type="checkbox" name="rest_rules[]" value="no_same_day" class="${checkboxClass}">
                                <span class="ml-2 text-sm text-gray-700">No jugar 2 veces al día</span>
                            </label>
                            <label class="flex items-center">
                                <!-- CAMBIO: value="no_consecutive_days"  POR  value="no_same_week" -->
                                <input type="checkbox" name="rest_rules[]" value="no_same_week" class="${checkboxClass}">
                                <span class="ml-2 text-sm text-gray-700">No jugar 2 veces a la semana</span>
                            </label>
                        </div>
                    </div>

                    <!-- NUEVO: ESTRATEGIA DE CALENDARIO -->
                    <div>
                        <label class="${labelClass}">Estrategia de Calendario</label>
                        <div class="bg-gray-50 p-3 rounded border border-gray-200">
                            <label class="flex items-start cursor-pointer group">
                                <div class="flex items-center h-5">
                                    <!-- 1. Input oculto con valor 0 (se envía si está desmarcado) -->
                                    <input type="hidden" name="interleave_categories" value="0">
                                    
                                    <!-- 2. Checkbox con valor 1 (sobrescribe el oculto si está marcado) -->
                                    <input type="checkbox" name="interleave_categories" value="1" id="interleaveCategories" checked class="${checkboxClass}">
                                </div>
                                <div class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900 group-hover:text-orange-600 transition-colors">
                                        Intercalar Categorías y Fuerzas
                                    </span>
                                    <span class="block text-xs text-gray-500 mt-0.5">
                                        Mezcla los partidos de diferentes categorías. Si se desmarca, se generan bloques completos por categoría.
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                </div>

                <!-- COLUMNA 2 -->
                <div class="space-y-4"> <!-- Aumentado a space-y-4 -->
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide border-b pb-1">Tiempos y Faltas</h3>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="${labelClass}">Periodos</label>
                            <input type="number" name="periods_per_game" value="4" class="${inputClass}">
                        </div>
                        <div>
                            <label class="${labelClass}">Min/Periodo</label>
                            <input type="number" name="game_duration" value="10" class="${inputClass}">
                        </div>
                        <div>
                            <label class="${labelClass}">Desc/Periodos</label>
                            <input type="number" name="rest_between_periods" value="2" class="${inputClass}">
                        </div>
                        <div>
                            <label class="${labelClass}">Desc/Partidos</label>
                            <input type="number" name="rest_between_games" value="10" class="${inputClass}">
                        </div>
                    </div>

                    <div>
                        <label class="${labelClass}">Tiempos Fuera por Equipo</label>
                        <input type="number" name="timeouts_per_game" value="5" class="${inputClass}">
                    </div>

                    <div>
                        <label class="${labelClass}">Límites de Faltas</label>
                        <div class="grid grid-cols-2 gap-3 bg-gray-50 p-3 rounded border border-gray-200">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Falta Técnica</label>
                                <input type="number" name="limit_foul_technical" value="2" class="${inputClass}">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Antideportiva</label>
                                <input type="number" name="limit_foul_unsportsmanlike" value="2" class="${inputClass}">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Personal</label>
                                <input type="number" name="limit_foul_personal" value="5" class="${inputClass}">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Descalificatoria</label>
                                <input type="number" name="limit_foul_disqualifying" value="1" class="${inputClass}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA 3 -->
                <div class="space-y-4"> <!-- Aumentado a space-y-4 -->
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide border-b pb-1">Logística</h3>
                    
                    <div>
                        <label class="${labelClass}">Días de Juego</label>
                        <div class="flex gap-4 bg-gray-50 p-3 rounded border border-gray-200">
                            <div class="w-1/2 flex flex-col gap-2">
                                ${renderDay(1, 'Lunes')}
                                ${renderDay(2, 'Martes')}
                                ${renderDay(3, 'Miércoles')}
                                ${renderDay(4, 'Jueves')}
                            </div>
                            <div class="w-1/2 flex flex-col gap-2">
                                ${renderDay(5, 'Viernes')}
                                ${renderDay(6, 'Sábado')}
                                ${renderDay(0, 'Domingo')}
                            </div>
                        </div>
                    </div>

                    <div class="flex-1">
                        <label class="${labelClass}">Canchas Disponibles</label>
                        <div class="border border-gray-300 rounded-md shadow-sm h-64 overflow-y-auto p-3">
                            ${courtCheckboxes.length ? courtCheckboxes : '<p class="text-sm text-gray-500 italic">No hay canchas registradas.</p>'}
                        </div>
                    </div>
                </div>

            </div>
        `;
        
        document.getElementById('calendarFormContent').innerHTML = content;
    }
    
    function populateViewForm(settings) {
        // --- MAPEO DE DATOS ---
        const typeMap = {
            'round_robin': 'Liga (Todos contra todos + Playoffs)',
            'elimination': 'Eliminatoria Directa',
            'double_elimination': 'Doble Eliminatoria',
            'groups': 'Fase de Grupos'
        };

        const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        const selectedDays = settings.days && Array.isArray(settings.days) 
            ? settings.days.map(d => dayNames[d]).join(', ') 
            : 'No definidos';

        const restRulesMap = {
            'no_same_day': 'No jugar 2 veces al día',
            'no_same_week': 'No jugar 2 veces a la semana'
        };
        const selectedRestRules = settings.rest_rules && Array.isArray(settings.rest_rules) 
            ? settings.rest_rules.map(rule => `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mr-1 mb-1">${restRulesMap[rule] || rule}</span>`).join('') 
            : '<span class="text-gray-400 text-sm">Sin restricciones</span>';

        const selectedCourts = settings.courts && Array.isArray(settings.courts)
            ? settings.courts.map(id => {
                const courtId = parseInt(id); 
                const court = allCourts.find(c => c.id === courtId);
                return court ? `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200 mr-1 mb-1">${court.name}</span>` : '';
            }).join('') 
            : '<span class="text-gray-400 text-sm">Sin asignar</span>';

        // --- DISEÑO FINAL (COLOR DURAZNO EN TIPO DE TORNEO) ---
        const content = `
            <div class="space-y-6">
                
                <!-- SECCIÓN 1: LOGÍSTICA -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 hover:border-orange-500 transition-colors duration-300">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wide flex items-center">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            Formato de Competencia
                        </h4>
                        <!-- AQUÍ EL CAMBIO A COLOR DURAZNO -->
                        <span class="mt-2 md:mt-0 px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200 shadow-sm">
                            ${typeMap[settings.tournament_type] || 'Desconocido'}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-y-4 gap-x-8 text-sm">
                        <div>
                            <span class="block text-xs text-slate-500 font-medium mb-1">Días de Juego</span>
                            <div class="text-slate-800 font-medium">${selectedDays}</div>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-500 font-medium mb-1">Reglas de Descanso</span>
                            <div>${selectedRestRules}</div>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-500 font-medium mb-1">Ubicación</span>
                            <div class="text-slate-800 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>${settings.location || 'No definida'}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GRILLA DE DOS COLUMNAS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- SECCIÓN 2: FECHAS Y HORARIOS -->
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full hover:border-orange-500 transition-colors duration-300">
                        <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                            <h4 class="text-sm font-bold text-gray-800 uppercase flex items-center">
                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Fechas y Horarios
                            </h4>
                        </div>
                        <div class="p-5 flex-1">
                            <div class="grid grid-cols-2 gap-4 mb-5">
                                <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                    <div class="text-[10px] text-gray-400 uppercase font-bold mb-1">Inicio</div>
                                    <div class="text-gray-800 font-mono font-bold text-sm">${settings.start_date ? settings.start_date.substring(0, 10) : '--/--/--'}</div>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                    <div class="text-[10px] text-gray-400 uppercase font-bold mb-1">Fin</div>
                                    <div class="text-gray-800 font-mono font-bold text-sm">${settings.end_date ? settings.end_date.substring(0, 10) : '--/--/--'}</div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between border-b border-dashed border-gray-100 pb-2">
                                    <span class="text-xs text-gray-500">Hora Inicio</span>
                                    <span class="text-sm font-semibold text-gray-800">${settings.start_time || '--:--'}</span>
                                </div>
                                <div class="flex items-center justify-between border-b border-dashed border-gray-100 pb-2">
                                    <span class="text-xs text-gray-500">Hora Fin</span>
                                    <span class="text-sm font-semibold text-gray-800">${settings.end_time || '--:--'}</span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <span class="block text-xs text-gray-500 font-medium mb-2">Canchas Habilitadas</span>
                                <div class="flex flex-wrap gap-1">${selectedCourts}</div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 3: REGLAMENTO -->
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full hover:border-orange-500 transition-colors duration-300">
                        <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                            <h4 class="text-sm font-bold text-gray-800 uppercase flex items-center">
                                <svg class="w-4 h-4 mr-2 text-orange-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                                Reglamento
                            </h4>
                        </div>
                        
                        <div class="p-5 flex-1 space-y-6">
                            
                            <!-- Bloque 1: Tiempos -->
                            <div>
                                <h5 class="text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Tiempos de Partido</h5>
                                <div class="grid grid-cols-2 gap-2 text-xs font-medium">
                                    <div class="bg-gray-50 p-2 rounded border border-gray-100 flex justify-between items-center">
                                        <span class="text-gray-600">Periodos</span>
                                        <span class="bg-white px-1.5 py-0.5 rounded text-gray-900 shadow-sm font-bold">${settings.periods_per_game}</span>
                                    </div>
                                    <div class="bg-gray-50 p-2 rounded border border-gray-100 flex justify-between items-center">
                                        <span class="text-gray-600">Min/Periodo</span>
                                        <span class="bg-white px-1.5 py-0.5 rounded text-gray-900 shadow-sm font-bold">${settings.game_duration}</span>
                                    </div>
                                    <div class="bg-gray-50 p-2 rounded border border-gray-100 flex justify-between items-center">
                                        <span class="text-gray-600">Desc/Pertido</span>
                                        <span class="bg-white px-1.5 py-0.5 rounded text-gray-900 shadow-sm font-bold">${settings.timeouts_per_game}</span>
                                    </div>
                                    <div class="bg-gray-50 p-2 rounded border border-gray-100 flex justify-between items-center">
                                        <span class="text-gray-600">Desc/Pariodo</span>
                                        <span class="bg-white px-1.5 py-0.5 rounded text-gray-900 shadow-sm font-bold">${settings.rest_between_periods || 0}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bloque 2: Faltas -->
                            <div>
                                <h5 class="text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Expulsión (Límites)</h5>
                                <div class="grid grid-cols-2 gap-2 text-xs font-medium">
                                    <div class="bg-gray-50 p-2 rounded border border-gray-100 flex justify-between items-center">
                                        <span class="text-gray-600">Técnicas</span>
                                        <span class="bg-white px-1.5 py-0.5 rounded text-gray-900 shadow-sm font-bold">${settings.limit_foul_technical}</span>
                                    </div>
                                    <div class="bg-gray-50 p-2 rounded border border-gray-100 flex justify-between items-center">
                                        <span class="text-gray-600">Antidep.</span>
                                        <span class="bg-white px-1.5 py-0.5 rounded text-gray-900 shadow-sm font-bold">${settings.limit_foul_unsportsmanlike}</span>
                                    </div>
                                    <div class="bg-gray-50 p-2 rounded border border-gray-100 flex justify-between items-center">
                                        <span class="text-gray-600">Personales</span>
                                        <span class="bg-white px-1.5 py-0.5 rounded text-gray-900 shadow-sm font-bold">${settings.limit_foul_personal}</span>
                                    </div>
                                    <div class="bg-gray-50 p-2 rounded border border-gray-100 flex justify-between items-center">
                                        <span class="text-gray-600">Descalif.</span>
                                        <span class="bg-white px-1.5 py-0.5 rounded text-gray-900 shadow-sm font-bold">${settings.limit_foul_disqualifying}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        `;
        
        document.getElementById('calendarFormContent').innerHTML = content;
    }

    function setCreateActions() {
        document.getElementById('calendarModalActions').innerHTML = `
            <!-- BOTÓN GENERAR CALENDARIO CAMBIADO A NARANJA -->
            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-orange-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:ml-3 sm:w-auto transition duration-150 ease-in-out">
                Generar Calendario
            </button>
            <button type="button" onclick="closeCalendarModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
        `;
    }

    function setViewActions(tournamentId, status) {
        let cloneButton = '';
        if (status === 'finished') {
            cloneButton = `<button type="button" onclick="cloneTournament(${tournamentId})" class="inline-flex justify-center rounded-md border border-transparent bg-orange-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:ml-3 sm:w-auto transition duration-150 ease-in-out">Crear Torneo Nuevo</button>`;
        }

        document.getElementById('calendarModalActions').innerHTML = `
            ${cloneButton}
            <button type="button" onclick="deleteCalendar(${tournamentId})" class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:ml-3 sm:w-auto">Eliminar Calendario</button>
            <button type="button" onclick="closeCalendarModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cerrar</button>
        `;
    }

    function closeCalendarModal() {
        document.getElementById('calendarModal').classList.add('hidden');
        resetCalendarModal();
    }

    async function handleCalendarSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const response = await fetch('/tournaments/generate-calendar', {
            method: 'POST', body: formData, headers: {
                'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        if (response.ok && data.success) {
            closeCalendarModal();
            showSuccessModal(data.message, data.redirect_url);
        } else {
            alert(data.message || 'Ocurrió un error.');
        }
    }

    async function deleteCalendar(tournamentId) {
        if (!confirm('¿Estás seguro de que quieres eliminar este calendario? Esta acción no se puede deshacer.')) return;
        const response = await fetch(`/tournaments/${tournamentId}/calendar`, {
            method: 'DELETE', headers: {
                'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        if (response.ok && data.success) {
            closeCalendarModal();
            showSuccessModal(data.message, data.redirect_url);
        } else {
            alert(data.message || 'Ocurrió un error al eliminar.');
        }
    }

    async function cloneTournament(tournamentId) {
        if (!confirm('¿Estás seguro de que quieres crear un torneo nuevo como copia exacta de este torneo (configuración y equipos registrados)?')) return;
        
        const saveBtn = document.querySelector(`button[onclick="cloneTournament(${tournamentId})"]`);
        const originalText = saveBtn ? saveBtn.innerText : '';
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.innerText = 'Clonando...';
        }

        try {
            const response = await fetch(`/tournaments/${tournamentId}/clone`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await response.json();
            if (response.ok && data.success) {
                closeCalendarModal();
                showSuccessModal(data.message, '/tournaments');
            } else {
                alert(data.message || 'Ocurrió un error al clonar el torneo.');
            }
        } catch (error) {
            console.error(error);
            alert('Ocurrió un error inesperado al procesar la solicitud.');
        } finally {
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerText = originalText;
            }
        }
    }

      // --- FUNCIÓN ACTUALIZADA: MODAL DE ÉXITO ---
    function showSuccessModal(message, redirectUrl) {
        const fullMessage = "¡Éxito! " + message;
        document.getElementById('successModalMessage').innerText = fullMessage;
        
        const acceptButton = document.querySelector('#successModal button[onclick="handleSuccessRedirect()"]');
        
        // CORRECCIÓN: Si redirectUrl es null o está vacío, no guardamos el atributo y cambiamos el texto del botón
        if (redirectUrl) {
            acceptButton.setAttribute('data-redirect-url', redirectUrl);
            acceptButton.innerText = "Aceptar"; // Texto normal cuando va a redirigir
        } else {
            acceptButton.removeAttribute('data-redirect-url');
            acceptButton.innerText = "Cerrar"; // Texto cuando solo es para cerrar
        }
        
        document.getElementById('successModal').classList.remove('hidden');
    }
    // --- FUNCIÓN ACTUALIZADA: MANEJO DE REDIRECCIÓN ---
    function handleSuccessRedirect() {
        const acceptButton = document.querySelector('#successModal button[onclick="handleSuccessRedirect()"]');
        const redirectUrl = acceptButton.getAttribute('data-redirect-url');
        
        // CORRECCIÓN: Solo redirigir si el atributo existe y tiene valor
        if (redirectUrl) {
            window.location.href = redirectUrl;
        } else {
            // Si no hay URL, solo cerrar el modal de éxito
            document.getElementById('successModal').classList.add('hidden');
        }
    }

    // === NUEVO SCRIPT PARA BÚSQUEDA AUTOMÁTICA (SERVER-SIDE) ===    
    const searchInput = document.querySelector('input[name="search"]');

    if (searchInput) {
        let timeout = null;

        searchInput.addEventListener('keyup', function (e) {
            clearTimeout(timeout);
            // Esperar 500ms para enviar el formulario
            timeout = setTimeout(function () {
                e.target.form.submit();
            }, 500);
        });
    }
        // --- FUNCIONES PARA REGLAMENTO ---

    // Variable global para saber si el usuario actual es Admin
    // Se basa en la lógica de tu código: Si no es árbitro ni entrenador, asumimos Admin
    const isAdmin = !('<?php echo e(auth()->user()->hasRole('Arbitro')); ?>' === '1' || '<?php echo e(auth()->user()->hasRole('Coach')); ?>' === '1');

    let quillInstance = null;
    let currentRulesTournamentName = '';
    let currentRulesTournamentLogoUrl = null;

    function initQuillEditor() {
        if (quillInstance) return quillInstance;

        // Barra de herramientas personalizada con fuentes, tamaños, estilos y colores
        const toolbarOptions = [
            [{ 'font': [] }],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            ['bold', 'italic', 'underline', 'strike'],        // Estilos de letra
            [{ 'color': [] }, { 'background': [] }],          // Colores de letra y fondo
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],     // Viñetas y números
            [{ 'align': [] }],
            ['clean']                                         // Limpiar formato
        ];

        quillInstance = new Quill('#rules_editor', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Escribe aquí las reglas específicas de este torneo...'
        });

        return quillInstance;
    }

    async function openRulesModal(tournamentId, tournamentName = '') {
        const modal = document.getElementById('rulesModal');
        const textarea = document.getElementById('rules_content');
        const saveBtn = document.getElementById('saveRulesButton');
        const title = document.getElementById('rulesModalTitle');
        const editorWrapper = document.getElementById('rules_editor_wrapper');
        
        currentRulesTournamentName = tournamentName;
        
        // Inicializar editor Quill
        const quill = initQuillEditor();
        
        // Resetear
        quill.root.innerHTML = '<p>Cargando reglamento...</p>';
        textarea.value = '';
        document.getElementById('rules_tournament_id').value = tournamentId;
        modal.classList.remove('hidden');

        try {
            // Obtener reglamento actual
            const response = await fetch(`/tournaments/${tournamentId}/rules`);
            const data = await response.json();
            
            currentRulesTournamentLogoUrl = data.logo_url;
            
            const content = data.reglamento || '';
            // Si el contenido ya es HTML, lo cargamos directo; si es texto plano, lo convertimos
            if (content.trim().startsWith('<') && content.trim().endsWith('>')) {
                quill.root.innerHTML = content;
            } else {
                const htmlContent = content.split('\n').map(line => line.trim() ? `<p>${line}</p>` : '<p><br></p>').join('');
                quill.root.innerHTML = htmlContent || '<p><br></p>';
            }

            // Lógica de Permisos / Edición
            const suffix = tournamentName ? `: ${tournamentName}` : '';
            if (isAdmin) {
                quill.enable(true);
                // Mostrar barra de herramientas
                const toolbar = editorWrapper.querySelector('.ql-toolbar');
                if (toolbar) toolbar.style.display = 'block';
                saveBtn.classList.remove('hidden'); // Mostrar botón guardar
                title.innerText = 'Editar Reglamento del Torneo' + suffix;
            } else {
                // Coach o Árbitro: Solo lectura
                quill.enable(false);
                // Ocultar barra de herramientas
                const toolbar = editorWrapper.querySelector('.ql-toolbar');
                if (toolbar) toolbar.style.display = 'none';
                saveBtn.classList.add('hidden'); // Ocultar botón guardar
                title.innerText = 'Ver Reglamento del Torneo' + suffix;
            }

        } catch (error) {
            console.error('Error al cargar reglamento:', error);
            quill.root.innerHTML = '<p class="text-red-500">Error al cargar el reglamento.</p>';
        }
    }

    function closeRulesModal() {
        document.getElementById('rulesModal').classList.add('hidden');
    }

    const storageUrl = "<?php echo e(asset('storage')); ?>/";
    let currentTournamentTeams = [];
    let currentActiveTournamentId = null;
    let currentActiveTournamentName = '';
    let currentActiveTournamentStatus = '';

    async function showTeamsListModal(tournamentId, tournamentName, status = '') {
        currentActiveTournamentId = tournamentId;
        currentActiveTournamentName = tournamentName;
        if (status) {
            currentActiveTournamentStatus = status;
        }

        // Hide/show Create Team button based on status (hidden only when tournament is finished)
        const btnCreateTeam = document.getElementById('btnCreateTeamFromList');
        if (btnCreateTeam) {
            if (currentActiveTournamentStatus === 'finished') {
                btnCreateTeam.classList.add('hidden');
            } else {
                btnCreateTeam.classList.remove('hidden');
            }
        }

        document.getElementById('teamsListModalTitle').innerText = `Equipos del Torneo: ${tournamentName}`;
        document.getElementById('teamsTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Cargando equipos...</td></tr>';
        document.getElementById('noTeamsMessage').classList.add('hidden');
        document.getElementById('teamsListModal').classList.remove('hidden');

        try {
            const response = await fetch(`/tournaments/${tournamentId}/teams/json`);
            if (!response.ok) throw new Error('Error al cargar equipos.');
            const teams = await response.json();
            
            currentTournamentTeams = teams;
            const tableBody = document.getElementById('teamsTableBody');
            tableBody.innerHTML = '';
            
            if (teams.length > 0) {
                teams.forEach(team => {
                    // 1. Imagen / Logo
                    let teamLogo = '';
                    if (team.image_path) {
                        let path = team.image_path;
                        let finalUrl = '';
                        if (path.startsWith('http')) {
                            finalUrl = path;
                        } else {
                            if (path.startsWith('/')) path = path.substring(1);
                            if (path.startsWith('storage/')) path = path.replace('storage/', '');
                            finalUrl = storageUrl + path;
                        }
                        teamLogo = `<img src="${finalUrl}" alt="${team.name}" class="h-10 w-10 rounded-full object-cover mx-auto">`;
                    } else {
                        teamLogo = `<div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold mx-auto">${team.name.substring(0, 1).toUpperCase()}</div>`;
                    }

                    // 2. Categoría
                    let categoryBadge = '<span class="text-gray-400">-</span>';
                    if (team.category === 'Femenil') {
                        categoryBadge = `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-pink-100 text-pink-800">Femenil</span>`;
                    } else if (team.category === 'Mixto') {
                        categoryBadge = `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Mixto</span>`;
                    } else if (team.category === 'Varonil') {
                        categoryBadge = `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Varonil</span>`;
                    }

                    // 3. Fuerza
                    let strengthBadge = `<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">${team.strength || '-'}</span>`;

                    // 4. Estatus
                    let statusBadge = '';
                    if (team.status === 'active') {
                        statusBadge = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>`;
                    } else if (team.status === 'pending') {
                        statusBadge = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">Pendiente</span>`;
                    } else if (team.status === 'suspended') {
                        statusBadge = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Suspendido (${team.suspension_games || 0} part.)</span>`;
                    }

                    // 5. Acciones
                    let actionsHtml = `
                        <div class="flex items-center justify-center space-x-2">
                            <button onclick="showPlayersListModalForTeam(${team.id})" class="text-green-600 hover:text-green-900" title="Ver Jugadores">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </button>
                            <button onclick="openTeamStatsModal(${team.id})" class="text-purple-600 hover:text-purple-900" title="Ver Estadísticas">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125z" />
                                </svg>
                            </button>
                    `;

                    if (isAdmin) {
                        actionsHtml += `
                            <button onclick="openEditTeamModal(${team.id})" class="text-indigo-600 hover:text-indigo-900" title="Editar Equipo">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                        `;
                    }
                    actionsHtml += `</div>`;

                    const row = `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">${actionsHtml}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">${teamLogo}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">${team.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">${categoryBadge}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">${strengthBadge}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">${statusBadge}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                document.getElementById('noTeamsMessage').classList.remove('hidden');
            }

        } catch (error) {
            console.error('Error:', error);
            document.getElementById('teamsTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error al cargar los equipos del torneo.</td></tr>';
        }
    }

    function closeTeamsListModal() {
        document.getElementById('teamsListModal').classList.add('hidden');
    }

    async function openTeamStatsModal(teamId) {
        const team = currentTournamentTeams.find(t => t.id === teamId);
        if (!team) return;

        document.getElementById('teamStatsModalTitle').innerText = `Estadísticas: ${team.name}`;
        const tableBody = document.getElementById('teamStatsTableBody');
        const noStatsMsg = document.getElementById('noStatsMessage');
        tableBody.innerHTML = '';
        noStatsMsg.classList.add('hidden');
        
        try {
            const response = await fetch(`/teams/${team.id}/stats`);
            const statsData = await response.json();
            
            if (Array.isArray(statsData)) {
                if (statsData.length === 0) {
                    noStatsMsg.classList.remove('hidden');
                }
                statsData.forEach(item => {
                    const points1 = item.stats.points1 ?? 0;
                    const points2 = item.stats.points2 ?? 0;
                    const points3 = item.stats.points3 ?? 0;
                    const totalPoints = (points1 * 1) + (points2 * 2) + (points3 * 3);
                    const fouls = item.stats.fouls ?? 0;
                    
                    const row = `
                        <tr>
                            <td class="px-3 py-2 font-medium text-gray-900">${item.name} (#${item.number})</td>
                            <td class="px-3 py-2 text-center text-gray-600">${points1}</td>
                            <td class="px-3 py-2 text-center text-gray-600">${points2}</td>
                            <td class="px-3 py-2 text-center text-gray-600">${points3}</td>
                            <td class="px-3 py-2 text-center font-bold bg-gray-50 text-gray-900">${totalPoints}</td>
                            <td class="px-3 py-2 text-center text-orange-600">${fouls}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                if (Object.keys(statsData).length === 0) {
                    noStatsMsg.classList.remove('hidden');
                }
                for (const [playerId, stats] of Object.entries(statsData)) {
                    const totalPoints = (stats.points1 * 1) + (stats.points2 * 2) + (stats.points3 * 3);
                    const row = `
                        <tr>
                            <td class="px-3 py-2 font-medium text-gray-900">${stats.name} (#${stats.number})</td>
                            <td class="px-3 py-2 text-center text-gray-600">${stats.points1}</td>
                            <td class="px-3 py-2 text-center text-gray-600">${stats.points2}</td>
                            <td class="px-3 py-2 text-center text-gray-600">${stats.points3}</td>
                            <td class="px-3 py-2 text-center font-bold bg-gray-50 text-gray-900">${totalPoints}</td>
                            <td class="px-3 py-2 text-center text-orange-600">${stats.fouls}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                }
            }
            document.getElementById('teamStatsModal').classList.remove('hidden');
        } catch(error) {
            console.error('Error al cargar estadísticas:', error);
            alert('No se pudieron cargar las estadísticas del equipo.');
        }
    }

    function closeTeamStatsModal() {
        document.getElementById('teamStatsModal').classList.add('hidden');
    }

    function openCreateTeamModalFromList() {
        document.getElementById('teamModalTitle').innerText = 'Crear Nuevo Equipo';
        document.getElementById('team_form_method').value = 'POST';
        document.getElementById('teamForm').action = '<?php echo e(route("teams.store")); ?>';

        document.getElementById('team_modal_name').value = '';
        document.getElementById('team_modal_coach_id').value = '';
        document.getElementById('team_modal_tournament_id').value = currentActiveTournamentId;
        document.getElementById('team_modal_tournament_id').disabled = false;
        document.getElementById('team_modal_status').value = 'active';
        document.getElementById('team_modal_category').value = '';
        document.getElementById('team_modal_strength').value = '';
        document.getElementById('team_modal_image').value = '';

        document.getElementById('teamModal').classList.remove('hidden');
    }

    function openEditTeamModal(teamId) {
        const team = currentTournamentTeams.find(t => t.id === teamId);
        if (!team) return;

        document.getElementById('teamModalTitle').innerText = 'Editar Equipo: ' + team.name;
        document.getElementById('team_form_method').value = 'PUT';
        document.getElementById('teamForm').action = '<?php echo e(route("teams.update", "TEAM_ID")); ?>'.replace('TEAM_ID', team.id);

        document.getElementById('team_modal_name').value = team.name;
        document.getElementById('team_modal_coach_id').value = team.coach_id || '';
        document.getElementById('team_modal_tournament_id').value = team.tournament_id || '';
        document.getElementById('team_modal_tournament_id').disabled = true; // Bloquea el select del torneo en edición también
        document.getElementById('team_modal_status').value = team.status || 'active';
        document.getElementById('team_modal_category').value = team.category || '';
        document.getElementById('team_modal_strength').value = team.strength || '';

        document.getElementById('teamModal').classList.remove('hidden');
    }

    function closeTeamModal() {
        document.getElementById('teamModal').classList.add('hidden');
    }

    let currentActiveTeamId = null;

    async function showPlayersListModalForTeam(teamId) {
        currentActiveTeamId = teamId;
        const team = currentTournamentTeams.find(t => t.id === teamId);
        const teamName = team ? team.name : 'Desconocido';

        // Hide/show Create Player button based on tournament status
        const btnCreatePlayer = document.getElementById('btnCreatePlayerFromList');
        if (btnCreatePlayer) {
            if (currentActiveTournamentStatus === 'active' || currentActiveTournamentStatus === 'finished') {
                btnCreatePlayer.classList.add('hidden');
            } else {
                btnCreatePlayer.classList.remove('hidden');
            }
        }

        document.getElementById('playersListModalTitle').innerText = `Jugadores del equipo: ${teamName}`;
        document.getElementById('playersTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Cargando jugadores...</td></tr>';
        document.getElementById('noPlayersMessage').classList.add('hidden');
        document.getElementById('playersListModal').classList.remove('hidden');

        try {
            const response = await fetch(`/teams/${teamId}/players/json`);
            if (!response.ok) throw new Error('Error al cargar jugadores.');
            const players = await response.json();
            
            const tableBody = document.getElementById('playersTableBody');
            tableBody.innerHTML = '';
            
            if (players.length > 0) {
                players.forEach(player => {
                    let playerImage = '';
                    if (player.image_path) {
                        let path = player.image_path;
                        let finalUrl = '';
                        if (path.startsWith('http')) {
                            finalUrl = path;
                        } else {
                            if (path.startsWith('/')) path = path.substring(1);
                            if (path.startsWith('storage/')) path = path.replace('storage/', '');
                            finalUrl = storageUrl + path;
                        }
                        playerImage = `<img src="${finalUrl}" alt="${player.name}" class="h-10 w-10 rounded-full object-cover mx-auto">`;
                    } else if (player.gender === 'hombre') {
                        playerImage = `<img src="/images/hombre.png" alt="${player.name}" class="h-10 w-10 rounded-full object-cover mx-auto">`;
                    } else if (player.gender === 'mujer') {
                        playerImage = `<img src="/images/mujer.png" alt="${player.name}" class="h-10 w-10 rounded-full object-cover mx-auto">`;
                    } else {
                        playerImage = `<div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold mx-auto">${player.name.substring(0, 1).toUpperCase()}</div>`;
                    }

                    let statusBadge = '';
                    if (player.status === 'suspended') {
                        statusBadge = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Suspendido (${player.suspension_games || 0} part.)</span>`;
                    } else if (player.status === 'expelled') {
                        statusBadge = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Expulsado</span>`;
                    } else {
                        statusBadge = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>`;
                    }

                    const row = `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-center">${playerImage}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">${player.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">${player.rfc || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">${player.number || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">${player.position || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">${statusBadge}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            } else {
                document.getElementById('noPlayersMessage').classList.remove('hidden');
            }

        } catch (error) {
            console.error('Error:', error);
            document.getElementById('playersTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error al cargar los jugadores del equipo.</td></tr>';
        }
    }

    function closePlayersListModal() {
        document.getElementById('playersListModal').classList.add('hidden');
    }

    function openCreatePlayerModalFromList() {
        document.getElementById('playerQuickForm').reset();
        document.getElementById('player_quick_team_id').value = currentActiveTeamId;

        // Determinar género por categoría del equipo para asignar avatar por default
        const team = currentTournamentTeams.find(t => t.id === currentActiveTeamId);
        let genderValue = '';
        if (team) {
            if (team.category === 'Varonil') {
                genderValue = 'hombre';
            } else if (team.category === 'Femenil') {
                genderValue = 'mujer';
            }
        }
        document.getElementById('player_quick_gender').value = genderValue;

        document.getElementById('playerQuickModal').classList.remove('hidden');
    }

    function closePlayerQuickModal() {
        document.getElementById('playerQuickModal').classList.add('hidden');
    }

    async function submitPlayerQuickForm(event) {
        event.preventDefault();
        const form = document.getElementById('playerQuickForm');
        const formData = new FormData(form);

        const btn = document.getElementById('playerQuickSaveButton');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Guardando...';

        try {
            const response = await fetch('<?php echo e(route("players.store")); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            });

            if (!response.ok) {
                const data = await response.json();
                alert(data.message || 'Error al guardar el jugador.');
                return;
            }

            const data = await response.json();
            if (data.success) {
                closePlayerQuickModal();
                showPlayersListModalForTeam(currentActiveTeamId);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error de conexión.');
        } finally {
            btn.disabled = false;
            btn.innerText = originalText;
        }
    }

    async function submitTeamForm(event) {
        event.preventDefault();
        const form = document.getElementById('teamForm');
        const formData = new FormData();

        const addField = (name, id) => {
            const el = document.getElementById(id);
            if (el) {
                if (el.type === 'file' && el.files.length > 0) {
                    formData.append(name, el.files[0]);
                } else {
                    formData.append(name, el.value);
                }
            }
        };

        formData.append('_token', '<?php echo e(csrf_token()); ?>');
        formData.append('_method', document.getElementById('team_form_method').value);

        addField('name', 'team_modal_name');
        addField('coach_id', 'team_modal_coach_id');
        
        const tourIdVal = document.getElementById('team_modal_tournament_id')?.value || currentActiveTournamentId;
        if (tourIdVal) {
            formData.append('tournament_id', tourIdVal);
        }

        addField('status', 'team_modal_status');
        addField('category', 'team_modal_category');
        addField('strength', 'team_modal_strength');
        addField('image', 'team_modal_image');

        const btn = document.getElementById('teamSaveButton');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Guardando...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error("ERROR DEL SERVIDOR (HTML):", errorText);
                alert("Error " + response.status + " del servidor:\n\n" + errorText.substring(0, 500) + "...");
                return;
            }

            const data = await response.json();

            if (response.ok) {
                closeTeamModal();
                showTeamsListModal(currentActiveTournamentId, currentActiveTournamentName);
            }

        } catch (error) {
            console.error('Error de red o JS:', error);
            alert('Error de conexión: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerText = originalText;
        }
    }

    async function saveRules(event) {
        event.preventDefault();
        
        // Sincronizar el contenido HTML del editor Quill con el textarea oculto
        if (quillInstance) {
            document.getElementById('rules_content').value = quillInstance.root.innerHTML;
        }

        const form = event.target;
        const formData = new FormData(form);
        const tournamentId = document.getElementById('rules_tournament_id').value;

        try {
            const response = await fetch(`/tournaments/${tournamentId}/rules`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Reutilizamos tu modal de éxito existente
                showSuccessModal(data.message, null); // null = no redirigir
                closeRulesModal();
            } else {
                alert(data.message || 'Ocurrió un error al guardar.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error inesperado.');
        }
    }

    function downloadPDF() {
        if (!quillInstance) return;
        const htmlContent = quillInstance.root.innerHTML;
        const tournamentName = currentRulesTournamentName || 'Torneo';

        // Comprobar si Quill tiene texto real
        const textCheck = quillInstance.getText().trim();
        if (!textCheck) {
            alert('No hay reglamento para descargar.');
            return;
        }

        const element = document.createElement('div');
        element.style.padding = '30px';
        element.style.fontFamily = 'Arial, sans-serif';
        element.style.backgroundColor = '#ffffff';

        let headerHTML = '';
        if (currentRulesTournamentLogoUrl) {
            headerHTML = `
                <div style="position: relative; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 24px; min-height: 70px; display: flex; align-items: center; justify-content: center; page-break-after: avoid;">
                    <h1 style="text-align: center; color: #1e293b; margin: 0; font-size: 24px; width: 100%; padding-right: 75px; padding-left: 75px;">Reglamento: ${tournamentName}</h1>
                    <img src="${currentRulesTournamentLogoUrl}" alt="Logo" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); width: 60px; height: 60px; border-radius: 12px; object-fit: cover; border: 1px solid #e2e8f0;">
                </div>
            `;
        } else {
            headerHTML = `
                <h1 style="text-align: center; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 24px; page-break-after: avoid;">Reglamento: ${tournamentName}</h1>
            `;
        }

        // Estilos para listas y elementos en PDF
        element.innerHTML = `
            <style>
                p { margin-bottom: 12px; font-size: 14px; line-height: 1.6; color: #334155; }
                ul { list-style-type: disc !important; padding-left: 24px !important; margin-bottom: 12px !important; display: block !important; }
                ol { list-style-type: decimal !important; padding-left: 24px !important; margin-bottom: 12px !important; display: block !important; }
                li { font-size: 14px; line-height: 1.6; color: #334155; margin-bottom: 4px; display: list-item !important; }
                strong { font-weight: bold; }
                u { text-decoration: underline; }
                s, strike { text-decoration: line-through; }
            </style>
            ${headerHTML}
            <div class="ql-editor">${htmlContent}</div>
        `;

        const opt = {
            margin:       0.75, 
            filename:     `Reglamento_${tournamentName.replace(/\s+/g, '_')}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
    }
        // ========================
    // LÓGICA COACH RÁPIDO
    // ========================
    function openQuickCoachModal() {
        document.getElementById('quickCoachForm').reset();
        document.getElementById('quickCoachModal').classList.remove('hidden');
    }

    function closeQuickCoachModal() {
        document.getElementById('quickCoachModal').classList.add('hidden');
    }

    async function submitQuickCoachForm(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerText;

        // UI Feedback
        submitBtn.innerText = 'Guardando...';
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75');

        try {
            const response = await fetch('<?php echo e(route("users.store")); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            let data;
            try {
                data = await response.json();
            } catch (e) {
                console.error("Error parseando JSON:", await response.text());
                throw new Error("El servidor devolvió un error inesperado (Error 500 o redirección).");
            }

            if (response.ok && data.success) {
                closeQuickCoachModal();
                const coachSelect = document.getElementById('team_modal_coach_id');
                const newOption = document.createElement('option');
                newOption.value = data.user.id;
                newOption.text = data.user.name;
                newOption.selected = true;
                coachSelect.appendChild(newOption);
                
                alert('Entrenador creado y asignado: ' + data.user.name);
            } else {
                let errorMsg = 'Error al crear el coach.\n';
                if (data.message) {
                    errorMsg += data.message + "\n";
                }
                if (data.errors) {
                    errorMsg += "Detalles:\n";
                    for (const [field, messages] of Object.entries(data.errors)) {
                        errorMsg += `- ${field}: ${messages.join(', ')}\n`;
                    }
                }
                alert(errorMsg);
            }
        } catch (error) {
            console.error("Error en catch:", error);
            alert('Error de conexión o del servidor: ' + error.message);
        } finally {
            submitBtn.innerText = originalBtnText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75');
        }
    }

    // ========================
    // LÓGICA DE FUERZAS DINÁMICAS
    // ========================
    function openAddStrengthModal() {
        document.getElementById('addStrengthForm').reset();
        document.getElementById('addStrengthModal').classList.remove('hidden');
    }

    function closeAddStrengthModal() {
        document.getElementById('addStrengthModal').classList.add('hidden');
    }

    async function submitStrengthForm(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerText;

        submitBtn.innerText = 'Guardando...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('<?php echo e(route("teams.strengths.store")); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                closeAddStrengthModal();
                const newOptionText = data.strength.name;
                
                const select = document.getElementById('team_modal_strength');
                const option = document.createElement('option');
                option.value = newOptionText;
                option.text = newOptionText;
                option.selected = true;
                select.appendChild(option);

                alert('Fuerza agregada exitosamente: ' + newOptionText);
            } else {
                alert('Error: ' + (data.message || 'No se pudo guardar la fuerza.'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error de conexión.');
        } finally {
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        }
    }

    function toggleGroupInputs() {
        const type = document.getElementById('tournament_type_select').value;
        const inputs = document.getElementById('groups_inputs');
        if (type === 'groups') {
            inputs.classList.remove('hidden');
        } else {
            inputs.classList.add('hidden');
        }
    }
</script><?php /**PATH C:\Users\luism\gemini-work\sistemaTorneos\resources\views/tournaments/index.blade.php ENDPATH**/ ?>