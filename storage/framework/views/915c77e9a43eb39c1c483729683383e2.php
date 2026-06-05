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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Contenedor Flex: Botón y Buscador -->
                    <div class="mb-6 flex flex-col md:flex-row items-center gap-4">
        
                        <!-- 1. El Botón (Condicional para ocultar a Árbitros) -->
                        <?php if(!auth()->user()->hasRole('Arbitro')): ?>
                            <button onclick="openCreateModal()" class="w-full md:w-auto shrink-0 bg-orange-600 text-white font-bold py-2 px-4 rounded hover:bg-orange-700 transition duration-150 ease-in-out">
                                Crear Nuevo Torneo
                            </button>
                        <?php endif; ?>

                        <!-- 2. El Buscador (Formulario GET) -->
                        <form action="<?php echo e(route('tournaments.index')); ?>" method="GET" class="relative w-full md:flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <!-- name="search" y value son claves para la paginación -->
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" 
                                placeholder="Buscar torneo por nombre, categoría, fechas o ubicación...">
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
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fuerza</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Inicio</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Fin</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
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

                                                <!-- NUEVO BOTÓN REGLAMENTO -->
                                                <button onclick="openRulesModal(<?php echo e($tournament->id); ?>)" class="text-gray-600 hover:text-gray-900" title="Ver Reglamento">
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
                                                        <button onclick="openViewCalendarModal(<?php echo e($tournament->id); ?>)" class="text-blue-600 hover:text-blue-900" title="Ver Configuración del Calendario">
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

                                        <!-- Se añade la clase "text-center" a cada celda de datos -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900"><?php echo e($tournament->name); ?></td>
                                        
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

                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500"><?php echo e($tournament->location); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <?php if($tournament->status == 'pending'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                            <?php elseif($tournament->status == 'active'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                            <?php elseif($tournament->status == 'finished'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-800 text-white">Terminado</span>
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
                    <form id="tournamentForm" onsubmit="submitForm(event)">
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
                                        <textarea id="modal_description" name="description" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full" rows="3"></textarea>                                    </div>
                                    
                                    <!-- CAMBIO: Categoría Automática -->
                                    <div class="mb-4 bg-orange-50 p-3 rounded border border-orange-100">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_category','value' => __('Categoría del Torneo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_category','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Categoría del Torneo'))]); ?>
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
                                        <div class="mt-1 text-sm text-gray-600 italic flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Este campo se define automáticamente según la categoría de los equipos inscritos.
                                        </div>
                                    </div>

                                    <!-- CAMBIO: Campo Fuerza Automático -->
                                    <div class="mb-4 bg-orange-50 p-3 rounded border border-orange-100">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_fuerza','value' => __('Fuerza del Torneo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_fuerza','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Fuerza del Torneo'))]); ?>
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
                                        <div class="mt-1 text-sm text-gray-600 italic flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Este campo se calcula automáticamente según el nivel (Fuerza) de los equipos inscritos.
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
                <div class="relative w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="rulesForm" onsubmit="saveRules(event)">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="tournament_id" id="rules_tournament_id">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="rulesModalTitle">Reglamento del Torneo</h3>
                                    
                                    <div class="mb-4">
                                        <!-- El textarea para editar/ver -->
                                        <textarea id="rules_content" name="reglamento" rows="10" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border" placeholder="Escribe aquí las reglas específicas de este torneo..."></textarea>
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
                            <button type="button" onclick="downloadPDF()" class="mr-2 inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition duration-150 ease-in-out sm:ml-3 sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

<script>
    const allCourts = <?php echo json_encode($courts, 15, 512) ?>;
    function openCreateModal() {
        resetForm();
        document.getElementById('modalTitle').innerText = 'Crear Nuevo Torneo';
        document.getElementById('form_method').value = 'POST';
        document.getElementById('tournamentForm').action = '<?php echo e(route("tournaments.store")); ?>';
        
        // --- CORRECCIÓN AQUÍ ---
        // Eliminamos esta línea porque ya no existe el input 'modal_category' en el formulario HTML
        // document.getElementById('modal_category').value = 'varonil'; 

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
saveButton.className = 'inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto';        document.getElementById('modal_name').value = tournament.name;
        document.getElementById('modal_description').value = tournament.description || '';
        if (tournament.start_date) document.getElementById('modal_start_date').value = tournament.start_date.substring(0, 10);
        if (tournament.end_date) document.getElementById('modal_end_date').value = tournament.end_date.substring(0, 10);
        document.getElementById('modal_location').value = tournament.location || '';
        
        
        document.getElementById('tournamentModal').classList.remove('hidden');
    }
    function closeModal() { document.getElementById('tournamentModal').classList.add('hidden'); }
    function resetForm() { document.getElementById('tournamentForm').reset(); }
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
            // Si hay error (ej. validación o error 500), recargamos también para limpiar
            // Nota: En producción podrías querer mostrar el error específico aquí
            console.error('Error al guardar:', response.statusText);
            window.location.reload(); 
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

    async function openViewCalendarModal(tournamentId) {
        resetCalendarModal();
        document.getElementById('calendar_tournament_id').value = tournamentId;
        try {
            const response = await fetch(`/tournaments/${tournamentId}/settings`);
            if (!response.ok) throw new Error('No se pudo cargar la configuración.');
            const settings = await response.json();
            document.getElementById('calendarModalTitle').innerText = 'Configuración del Calendario';
            populateViewForm(settings);
            setViewActions(tournamentId);
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                        <div>
                            <span class="block text-xs text-slate-500 font-medium mb-1">Días de Juego</span>
                            <div class="text-slate-800 font-medium">${selectedDays}</div>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-500 font-medium mb-1">Reglas de Descanso</span>
                            <div>${selectedRestRules}</div>
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

    function setViewActions(tournamentId) {
        document.getElementById('calendarModalActions').innerHTML = `
            <button type="button" onclick="deleteCalendar(${tournamentId})" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto">Eliminar Calendario</button>
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

    async function openRulesModal(tournamentId) {
        const modal = document.getElementById('rulesModal');
        const textarea = document.getElementById('rules_content');
        const saveBtn = document.getElementById('saveRulesButton');
        const title = document.getElementById('rulesModalTitle');
        
        // Resetear
        textarea.value = 'Cargando...';
        document.getElementById('rules_tournament_id').value = tournamentId;
        modal.classList.remove('hidden');

        try {
            // Obtener reglamento actual
            const response = await fetch(`/tournaments/${tournamentId}/rules`);
            const data = await response.json();
            
            textarea.value = data.reglamento || '';

            // Lógica de Permisos
            if (isAdmin) {
                textarea.readOnly = false;
                textarea.classList.remove('bg-gray-100', 'cursor-not-allowed');
                textarea.classList.add('bg-white');
                saveBtn.classList.remove('hidden'); // Mostrar botón guardar
                title.innerText = 'Editar Reglamento del Torneo';
            } else {
                // Coach o Árbitro: Solo lectura
                textarea.readOnly = true;
                textarea.classList.add('bg-gray-100', 'cursor-not-allowed');
                textarea.classList.remove('bg-white');
                saveBtn.classList.add('hidden'); // Ocultar botón guardar
                title.innerText = 'Ver Reglamento del Torneo';
            }

        } catch (error) {
            console.error('Error al cargar reglamento:', error);
            textarea.value = 'Error al cargar el reglamento.';
        }
    }

    function closeRulesModal() {
        document.getElementById('rulesModal').classList.add('hidden');
    }

    async function saveRules(event) {
        event.preventDefault();
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
        const textContent = document.getElementById('rules_content').value;
        const modalTitle = document.getElementById('rulesModalTitle').innerText;
        
        // Extraer nombre del torneo
        const tournamentName = modalTitle.split('Reglamento del Torneo: ')[1] || 'Torneo';

        if (!textContent.trim()) {
            alert('No hay reglamento para descargar.');
            return;
        }

        const element = document.createElement('div');
        element.style.padding = '20px';
        element.style.fontFamily = 'Arial, sans-serif';
        
        // --- CAMBIO CLAVE: Dividir el texto en párrafos <p> individuales ---
        // Esto permite que el "page-break-inside" funcione línea por línea
        const paragraphs = textContent.split('\n');
        let contentHTML = '';

        paragraphs.forEach(line => {
            if (line.trim() !== '') {
                // Evita cortar esta línea específica
                contentHTML += `<p style="margin-bottom: 8px; font-size: 14px; line-height: 1.5; color: #333; page-break-inside: avoid;">${line}</p>`;
            } else {
                // Si hay una línea vacía en el textarea, la respetamos
                contentHTML += `<br>`;
            }
        });

        element.innerHTML = `
            <h1 style="text-align: center; color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; page-break-after: avoid;">Reglamento: ${tournamentName}</h1>
            <div>${contentHTML}</div>
        `;

        const opt = {
            // Aumentamos el margen a 1.0 para evitar que el texto quede pegado al borde o cortado
            margin:       1.0, 
            filename:     `Reglamento_${tournamentName.replace(/\s+/g, '_')}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
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
</script><?php /**PATH C:\xampp\htdocs\sistemaTorneos\resources\views/tournaments/index.blade.php ENDPATH**/ ?>