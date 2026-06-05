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
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Contenedor Flex: Botón y Buscador -->
                    <div class="mb-6 flex flex-col md:flex-row items-center gap-4">
                        
                        <!-- 1. El Botón -->
                        <button onclick="openCreateModal()" class="w-full md:w-auto shrink-0 bg-orange-600 text-white font-bold py-2 px-4 rounded hover:bg-orange-700 transition duration-150 ease-in-out">
                            Crear Nuevo Jugador
                        </button>

                        <!-- 2. El Buscador (Formulario GET) -->
                        <form action="<?php echo e(route('players.index')); ?>" method="GET" class="relative w-full md:flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <!-- Nota: Ajustado padding-left a pl-10 para que el texto no choque con el icono -->
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" 
                                placeholder="Buscar por nombre, RFC, número, posición, estatus o equipo...">
                        </form>

                    </div>

                    <?php if(session('message')): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?php echo e(session('message')); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- INICIO CAMBIO: Envolver tabla para Responsive (Scroll Horizontal) -->
                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <!-- CAMBIO: Columna Acciones movida al inicio -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">RFC</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">CURP</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Posición</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estatus</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Equipo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <!-- CAMBIO: Columna Acciones movida al inicio -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex items-center justify-center space-x-3">

                                                                                            <button onclick="openEditModal(<?php echo e($player->toJson()); ?>)" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </button>

                                                 <button onclick="openViewModal(<?php echo e($player->toJson()); ?>)" class="text-green-600 hover:text-green-900" title="Ver Credencial">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </button>

                                                <form action="<?php echo e(route('players.destroy', $player)); ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este jugador?');" class="inline">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        <!-- Resto de columnas -->
                                        <td class="px-6 py-4 whitespace-nowrap flex justify-center items-center">
                                            <?php if($player->image_path): ?>
                                                <!-- Si hay foto subida, muestra la foto -->
                                                <img src="<?php echo e(asset('storage/' . $player->image_path)); ?>" alt="<?php echo e($player->name); ?>" class="h-10 w-10 rounded-full object-cover">
                                            <?php elseif($player->gender === 'hombre'): ?>
                                                <!-- Si NO hay foto y es HOMBRE, muestra imagen default hombre -->
                                                <img src="<?php echo e(asset('images/hombre.png')); ?>" alt="Hombre" class="h-10 w-10 rounded-full object-cover">
                                            <?php elseif($player->gender === 'mujer'): ?>
                                                <!-- Si NO hay foto y es MUJER, muestra imagen default mujer -->
                                                <img src="<?php echo e(asset('images/mujer.png')); ?>" alt="Mujer" class="h-10 w-10 rounded-full object-cover">
                                            <?php else: ?>
                                                <!-- Si NO hay foto y NO hay género, muestra la letra -->
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold">
                                                    <?php echo e(substr($player->name, 0, 1)); ?>

                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900"><?php echo e($player->name); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            <?php echo e($player->rfc); ?>

                                        </td>
                                                                                <!-- Agrega este bloque después del <td> de RFC -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            <?php echo e($player->curp); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500"><?php echo e($player->number); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500"><?php echo e($player->position); ?></td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <?php if($player->status == 'active'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                            <?php elseif($player->status == 'suspended'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Suspendido (<?php echo e($player->suspension_games); ?> part.)</span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Expulsado</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500"><?php echo e($player->team->name); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <!-- El colspan sigue siendo 9 -->
                                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">No hay jugadores registrados.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- FIN CAMBIO -->

                    <!-- CONTROLES DE PAGINACIÓN -->
                    <div class="mt-4">
                        <?php echo e($players->links()); ?>

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

    <!-- Modal para Crear/Editar Jugador (AJUSTE FINAL: BOTÓN CANCELAR Y ARCHIVO NARANJA) -->
    <div id="playerModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-2 sm:p-4">
                <div class="relative w-full max-w-3xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all">
                    <form id="playerForm" onsubmit="submitForm(event)" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        <input type="hidden" name="suspension_games" value="0">
                        
                        <!-- TÍTULO (SIN "X") -->
                        <div class="bg-white px-4 py-3 sm:p-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-2 border-b pb-1" id="modalTitle">
                                Crear Nuevo Jugador
                            </h3>
                        </div>

                        <!-- NAVEGACIÓN DE PESTAÑAS -->
                        <div class="px-4 pt-2 sm:px-6 sm:pt-4">
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                    <button type="button" onclick="switchTab('personal')" id="tab-btn-personal" class="text-orange-600 border-orange-500 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                                        Datos Personales
                                    </button>
                                    <button type="button" onclick="switchTab('emergency')" id="tab-btn-emergency" class="text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors">
                                        Contacto de Emergencia
                                    </button>
                                </nav>
                            </div>
                        </div>

                        <div class="px-4 py-4 sm:p-6">
                            
                            <!-- PESTAÑA 1: DATOS PERSONALES -->
                            <div id="tab-personal">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    
                                    <div class="md:col-span-2">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_name','value' => __('Nombre Completo *')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre Completo *'))]); ?>
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
                                    </div>
                                    
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_rfc','value' => __('RFC')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_rfc','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('RFC'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_rfc','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'rfc','maxlength' => '13','placeholder' => 'Ej: ABCD123456XYZ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_rfc','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'rfc','maxlength' => '13','placeholder' => 'Ej: ABCD123456XYZ']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_curp','value' => __('CURP *')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_curp','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('CURP *'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_curp','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'curp','maxlength' => '18','placeholder' => '18 caracteres','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_curp','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'curp','maxlength' => '18','placeholder' => '18 caracteres','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_number','value' => __('Número *')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_number','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Número *'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_number','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'number','name' => 'number','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_number','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'number','name' => 'number','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_position','value' => __('Posición')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_position','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Posición'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_position','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'position','placeholder' => 'Ej: Base, Alero']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_position','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'position','placeholder' => 'Ej: Base, Alero']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_gender','value' => __('Sexo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_gender','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Sexo'))]); ?>
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
                                        <select id="modal_gender" name="gender" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                            <option value="">Seleccionar...</option>
                                            <option value="hombre">Hombre</option>
                                            <option value="mujer">Mujer</option>
                                        </select>
                                    </div>

                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_blood_type','value' => __('Tipo de Sangre')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_blood_type','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Tipo de Sangre'))]); ?>
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
                                        <select id="modal_blood_type" name="blood_type" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                            <option value="">Seleccionar...</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                        </select>
                                    </div>

                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_team_id','value' => __('Equipo')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_team_id','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Equipo'))]); ?>
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
                                        <select id="modal_team_id" name="team_id" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($team->id); ?>"><?php echo e($team->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_status','value' => __('Estatus')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_status','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Estatus'))]); ?>
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
                                        <select id="modal_status" name="status" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full">
                                            <option value="active">Activo</option>
                                            <option value="suspended">Suspendido</option>
                                            <option value="expelled">Expulsado</option>
                                        </select>
                                    </div>

                                    <!-- FOTO AL LADO DE ESTATUS + BOTÓN NARANJA -->
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_image','value' => __('Foto *')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_image','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Foto *'))]); ?>
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
                                        <!-- Nota: Clases file:bg-orange-50 y file:text-orange-700 añadidas -->
                                        <input type="file" name="image" id="modal_image" class="mt-1 block w-full text-sm text-gray-500 focus:outline-none focus:border-orange-500 file:mr-4 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 file:hover:bg-orange-100" />
                                    </div>
                                </div>
                            </div>

                            <!-- PESTAÑA 2: CONTACTO DE EMERGENCIA -->
                            <div id="tab-emergency" class="hidden">
                                <div class="bg-orange-50 p-4 rounded-lg border border-orange-100">
                                    <h4 class="text-sm font-bold text-orange-800 mb-3 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                        Información de Emergencia
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="col-span-1 md:col-span-2">
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_emergency_name','value' => __('Nombre del Contacto')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_emergency_name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre del Contacto'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_emergency_name','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'emergency_contact_name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_emergency_name','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'emergency_contact_name']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_emergency_relationship','value' => __('Parentesco')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_emergency_relationship','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Parentesco'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_emergency_relationship','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'emergency_contact_relationship','placeholder' => 'Ej: Padre, Esposa']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_emergency_relationship','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'emergency_contact_relationship','placeholder' => 'Ej: Padre, Esposa']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_emergency_phone','value' => __('Teléfono')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_emergency_phone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Teléfono'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modal_emergency_phone','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'emergency_contact_phone','placeholder' => 'Ej: 55 1234 5678']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal_emergency_phone','class' => 'block mt-1 w-full focus:border-orange-500 focus:ring-orange-500','type' => 'text','name' => 'emergency_contact_phone','placeholder' => 'Ej: 55 1234 5678']); ?>
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

                                        <div class="col-span-1 md:col-span-2">
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modal_emergency_address','value' => __('Dirección')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modal_emergency_address','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Dirección'))]); ?>
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
                                            <textarea id="modal_emergency_address" name="emergency_contact_address" rows="2" class="border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm mt-1 block w-full"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <!-- FOOTER RESTAURADO CON BOTÓN CANCELAR -->
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
<?php $component->withAttributes(['type' => 'submit','id' => 'saveButton','class' => 'w-full sm:ml-3 sm:w-auto']); ?>
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
                            <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para Ver Jugador (Credencial Actualizado) -->
    <div id="viewModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="closeViewModal()"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-sm bg-white rounded-xl shadow-xl overflow-visible">
                    
                    <!-- PUNTO DE ESTATUS (Esquina superior derecha) -->
                    <div id="view_status_dot" class="absolute top-4 right-4 w-4 h-4 rounded-full border-2 border-white shadow-sm z-20"></div>

                    <!-- Foto Grande Flotante -->
                    <div id="view_image_container" class="absolute -top-20 left-1/2 -translate-x-1/2 h-40 w-40 rounded-full border-4 border-white shadow-xl bg-gray-200 overflow-hidden flex items-center justify-center z-50">
                        <img id="view_image" src="" alt="" class="h-full w-full object-cover hidden">
                        <span id="view_initial" class="text-6xl font-bold text-gray-500 hidden"></span>
                    </div>

                    <!-- Contenido de la Credencial -->
                    <div class="pt-28 pb-6 px-6 text-center relative z-10">
                        
                        <h3 id="view_name" class="text-xl font-bold leading-6 text-gray-900"></h3>
                        
                        <!-- 1. Línea Principal: Torneo | Equipo | Número | Posición -->
                        <div class="flex justify-center items-center gap-2 text-sm text-gray-600 mt-1 font-medium flex-wrap">
                            <span id="view_tournament">-</span>
                            <span class="text-gray-300">|</span>
                            <span id="view_team" class="text-orange-600">-</span>
                            <span class="text-gray-300">|</span>
                            <span># <span id="view_number">-</span></span>
                            <span class="text-gray-300">|</span>
                            <span id="view_position">-</span>
                        </div>

                        <!-- 2. Segunda Línea: Fuerza | Categoría -->
                        <div class="flex justify-center items-center gap-2 text-xs text-gray-500 mt-0 font-medium uppercase">
                            <span id="view_strength">-</span>
                            <span class="text-gray-300">|</span>
                            <span id="view_category">-</span>
                        </div>

                        <div class="mt-6 text-left">
                            
                            <!-- SECCIÓN GRIS (Informativa) -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 relative">
                                
                                <!-- Botón para cambiar vista -->
                                <button onclick="toggleCredentialView()" class="absolute top-2 right-2 text-gray-400 hover:text-orange-600 transition-colors p-1" title="Cambiar vista">
                                    <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                    </svg>
                                </button>

                                <!-- Título Dinámico (Estilo Naranja) -->
                                <p id="infoSectionTitle" class="text-xs font-bold text-orange-600 uppercase mb-3 border-b border-orange-200 pb-2">DATOS PERSONALES</p>

                                <!-- VISTA 1: DATOS PERSONALES -->
                                <div id="view_personal_info" class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">RFC:</span>
                                        <span id="view_rfc" class="font-semibold text-gray-900 text-sm">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">CURP:</span>
                                        <span id="view_curp" class="font-semibold text-gray-900 text-sm">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Sexo:</span>
                                        <span id="view_gender" class="font-semibold text-gray-900 text-sm">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Tipo Sangre:</span>
                                        <span id="view_blood_type" class="font-semibold text-gray-900 text-sm">-</span>
                                    </div>
                                </div>

                                <!-- VISTA 2: DATOS DE EMERGENCIA -->
                                <div id="view_emergency_info" class="space-y-2 hidden">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Contacto:</span>
                                        <span id="view_emergency_name" class="font-semibold text-gray-900 text-sm text-right">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Parentesco:</span>
                                        <span id="view_emergency_relationship" class="font-semibold text-gray-900 text-sm">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Teléfono:</span>
                                        <span id="view_emergency_phone" class="font-semibold text-gray-900 text-sm">-</span>
                                    </div>
                                    <div class="text-sm pt-1 border-t border-gray-200 mt-2">
                                        <span class="text-gray-500 block text-xs mb-1">Dirección:</span>
                                        <span id="view_emergency_address" class="text-gray-900 font-medium">-</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="button" onclick="closeViewModal()" class="w-full inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            resetForm();
            document.getElementById('modalTitle').innerText = 'Crear Nuevo Jugador';
            document.getElementById('form_method').value = 'POST';
            document.getElementById('playerForm').action = '<?php echo e(route("players.store")); ?>';
            const saveButton = document.getElementById('saveButton');
            saveButton.className = 'inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto';
            document.getElementById('modal_status').value = 'active';
            document.getElementById('playerModal').classList.remove('hidden');
        }

        function openEditModal(player) {
            resetForm();
            document.getElementById('modalTitle').innerText = 'Editar Jugador: ' + player.name;
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('playerForm').action = '<?php echo e(route("players.update", ":id")); ?>'.replace(':id', player.id);

            const saveButton = document.getElementById('saveButton');
            saveButton.className = 'inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:ml-3 sm:w-auto';
            
            // --- Datos Personales ---
            document.getElementById('modal_name').value = player.name;
            document.getElementById('modal_rfc').value = player.rfc || '';
            document.getElementById('modal_curp').value = player.curp || ''; // NUEVO
            document.getElementById('modal_number').value = player.number || '';
            document.getElementById('modal_position').value = player.position || '';
            document.getElementById('modal_gender').value = player.gender || '';
            document.getElementById('modal_blood_type').value = player.blood_type || ''; // NUEVO
            document.getElementById('modal_team_id').value = player.team_id;
            document.getElementById('modal_status').value = player.status || 'active';

            // --- Contacto de Emergencia ---
            document.getElementById('modal_emergency_name').value = player.emergency_contact_name || ''; // NUEVO
            document.getElementById('modal_emergency_relationship').value = player.emergency_contact_relationship || ''; // NUEVO
            document.getElementById('modal_emergency_phone').value = player.emergency_contact_phone || ''; // NUEVO
            document.getElementById('modal_emergency_address').value = player.emergency_contact_address || ''; // NUEVO

            document.getElementById('playerModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('playerModal').classList.add('hidden');
        }

        function resetForm() {
            document.getElementById('playerForm').reset();
        }

                async function submitForm(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    // --- MANEJO DE ERRORES DE VALIDACIÓN ---
                    if (response.status === 422) {
                        const data = await response.json();
                        let errorMessages = "Por favor corrige los siguientes errores:\n\n";
                        
                        // Iteramos sobre los errores devueltos por Laravel
                        for (const [field, messages] of Object.entries(data.errors)) {
                            messages.forEach(msg => {
                                errorMessages += `- ${msg}\n`;
                            });
                        }
                        
                        // Mostramos el mensaje en una alerta
                        alert(errorMessages);
                    } else {
                        // Error genérico (ej: Error 500)
                        alert('Ocurrió un error inesperado en el servidor.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error inesperado de conexión.');
            }
        }
        
        const storageUrl = '<?php echo e(asset('storage')); ?>/';

        function openViewModal(player) {
            // --- 1. LÓGICA DE IMAGEN ---
            const imgEl = document.getElementById('view_image');
            const initialEl = document.getElementById('view_initial');
            const container = document.getElementById('view_image_container');

            if (player.image_path) {
                imgEl.src = '<?php echo e(asset('storage')); ?>/' + player.image_path;
                imgEl.classList.remove('hidden');
                initialEl.classList.add('hidden');
            } else if (player.gender === 'hombre') {
                imgEl.src = '/images/hombre.png'; 
                imgEl.classList.remove('hidden');
                initialEl.classList.add('hidden');
            } else if (player.gender === 'mujer') {
                imgEl.src = '/images/mujer.png'; 
                imgEl.classList.remove('hidden');
                initialEl.classList.add('hidden');
            } else {
                imgEl.classList.add('hidden');
                initialEl.classList.remove('hidden');
                initialEl.innerText = player.name ? player.name.charAt(0).toUpperCase() : '?';
            }

            // --- 2. DATOS BÁSICOS ---
            document.getElementById('view_name').innerText = player.name;

            const teamName = player.team?.name || 'Sin equipo';
            const tournamentName = player.team?.tournament?.name || 'Sin Torneo';
            const categoryName = player.team?.category || '-';
            const strengthName = player.team?.strength || '-';

            document.getElementById('view_tournament').innerText = tournamentName;
            document.getElementById('view_team').innerText = teamName;
            document.getElementById('view_number').innerText = player.number || '-';
            document.getElementById('view_position').innerText = player.position || '-';

            document.getElementById('view_strength').innerText = strengthName;
            document.getElementById('view_category').innerText = categoryName;

            // --- 3. COLOR DE ESTATUS ---
            const statusDot = document.getElementById('view_status_dot');
            statusDot.className = 'absolute top-4 right-4 w-4 h-4 rounded-full border-2 border-white shadow-sm z-20 ';
            
            if (player.status === 'active') {
                statusDot.classList.add('bg-green-500');
            } else if (player.status === 'suspended') {
                statusDot.classList.add('bg-yellow-500');
            } else {
                statusDot.classList.add('bg-red-500');
            }

            // --- 4. DATOS PERSONALES (SIN ENMASCARAMIENTO) ---
            document.getElementById('view_rfc').innerText = player.rfc || '-';
            document.getElementById('view_curp').innerText = player.curp || '-';
            
            let genderText = player.gender || '-';
            if (player.gender) {
                genderText = player.gender.charAt(0).toUpperCase() + player.gender.slice(1);
            }
            document.getElementById('view_gender').innerText = genderText;
            document.getElementById('view_blood_type').innerText = player.blood_type || '-';

            // --- 5. DATOS DE EMERGENCIA ---
            document.getElementById('view_emergency_name').innerText = player.emergency_contact_name || '-';
            document.getElementById('view_emergency_relationship').innerText = player.emergency_contact_relationship || '-';
            document.getElementById('view_emergency_phone').innerText = player.emergency_contact_phone || '-';
            document.getElementById('view_emergency_address').innerText = player.emergency_contact_address || '-';

            // --- 6. RESET Y MOSTRAR ---
            document.getElementById('view_personal_info').classList.remove('hidden');
            document.getElementById('view_emergency_info').classList.add('hidden');
            document.getElementById('infoSectionTitle').innerText = "DATOS PERSONALES";
            document.getElementById('infoSectionTitle').className = "text-xs font-bold text-orange-600 uppercase mb-3 border-b border-orange-200 pb-2";

            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        // Función para alternar entre Personal y Emergencia
        function toggleCredentialView() {
            const personalSection = document.getElementById('view_personal_info');
            const emergencySection = document.getElementById('view_emergency_info');
            const title = document.getElementById('infoSectionTitle');

            if (personalSection.classList.contains('hidden')) {
                // Si estaba oculto, mostrar Personal
                personalSection.classList.remove('hidden');
                emergencySection.classList.add('hidden');
                title.innerText = "DATOS PERSONALES";
                
                // CORRECCIÓN: Aseguramos que vuelva a ser NARANJA
                title.className = "text-xs font-bold text-orange-600 uppercase mb-3 border-b border-orange-200 pb-2";
                
            } else {
                // Si estaba visible, mostrar Emergencia
                personalSection.classList.add('hidden');
                emergencySection.classList.remove('hidden');
                title.innerText = "CONTACTO DE EMERGENCIA";
                
                // Mantenemos el estilo NARANJA
                title.className = "text-xs font-bold text-orange-600 uppercase mb-3 border-b border-orange-200 pb-2";
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
        // =====================================================

        function switchTab(tabName) {
            // Ocultar todos los contenidos
            document.getElementById('tab-personal').classList.add('hidden');
            document.getElementById('tab-emergency').classList.add('hidden');

            // Resetear estilos de botones
            document.getElementById('tab-btn-personal').classList.remove('text-orange-600', 'border-orange-500');
            document.getElementById('tab-btn-personal').classList.add('text-gray-500', 'border-transparent');
            
            document.getElementById('tab-btn-emergency').classList.remove('text-orange-600', 'border-orange-500');
            document.getElementById('tab-btn-emergency').classList.add('text-gray-500', 'border-transparent');

            // Mostrar el seleccionado
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            
            // Activar estilo del botón
            document.getElementById('tab-btn-' + tabName).classList.remove('text-gray-500', 'border-transparent');
            document.getElementById('tab-btn-' + tabName).classList.add('text-orange-600', 'border-orange-500');
        }
    </script><?php /**PATH C:\xampp\htdocs\sistemaTorneos\resources\views/players/index.blade.php ENDPATH**/ ?>