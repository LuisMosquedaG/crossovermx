<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>
        
        <link rel="icon" type="image/png" href="<?php echo e(asset('logo.png')); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

        <!-- Estilos para el Fondo Dinámico -->
        <style>
            body {
                /* Fondo base claro (igual al login) */
                background-color: #f8fafc; 
            }

            /* Animación de movimiento en toda la pantalla */
            @keyframes blob-full-screen {
                0% { transform: translate(0, 0) scale(1); }
                33% { transform: translate(40vw, 30vh) scale(1.1); }
                66% { transform: translate(-30vw, -20vh) scale(0.9); }
                100% { transform: translate(0, 0) scale(1); }
            }

            .animate-blob-full {
                animation: blob-full-screen 12s infinite ease-in-out;
            }
            
            .animation-delay-2000 { animation-delay: 2s; }
            .animation-delay-4000 { animation-delay: 4s; }
        </style>
    </head>
    <body class="font-sans antialiased relative min-h-screen">
        
        <!-- CAPA 1: FONDO ANIMADO (Z-INDEX NEGATIVO) -->
        
        <!-- 1. Patrón de Puntos -->
        <div class="fixed inset-0 -z-20 h-full w-full bg-white bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:24px_24px] opacity-60"></div>

        <!-- 2. Blobs de Color -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <!-- Nube Naranja -->
            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-orange-300 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob-full"></div>
            <!-- Nube Azul -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob-full animation-delay-2000"></div>
            <!-- Nube Clara -->
            <div class="absolute -bottom-40 left-1/3 w-[500px] h-[500px] bg-orange-100 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob-full animation-delay-4000"></div>
        </div>


        <!-- CAPA 2: CONTENIDO DE LA APLICACIÓN (Z-INDEX 10 PARA QUE ESTÉ ARRIBA) -->
        <div class="relative z-10 min-h-screen">
            
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <!-- Añadí bg-white/90 y backdrop-blur para que el título también se integre con el fondo -->
                <header class="bg-white/90 backdrop-blur-sm shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <!-- No añadimos fondo aquí para que se vea el de atrás. 
                 Tus tarjetas internas (bg-white) normalmente taparán el fondo en el área de contenido. -->
            <main class="relative z-10">
                <?php echo e($slot); ?>

            </main>
        </div>

            <!-- ............................................................................ -->
    <!-- MODAL DE TÉRMINOS Y CONDICIONES (Solo se muestra si no ha aceptado)         -->
    <!-- ............................................................................ -->
    <?php if(auth()->check() && is_null(auth()->user()->terms_accepted_at) && !auth()->user()->hasRole('Super Admin')): ?>
        
        <!-- Fondo Oscuro / Backdrop (No cierra al hacer click) -->
        <div id="termsModalBackdrop" class="fixed inset-0 z-[60] bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 flex items-center justify-center p-4">
            
            <!-- Contenedor del Modal -->
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col animate-[blob-full-screen_0.5s_ease-out] ring-1 ring-gray-900/5">
                
                <!-- HEADER -->
                <div class="p-6 border-b border-gray-100 flex-shrink-0 bg-gray-50 rounded-t-2xl">
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Bienvenido a CrossoverMX</h2>
                    <p class="text-sm text-gray-500 mt-1">Por favor, revisa y acepta los siguientes documentos para continuar.</p>
                </div>

                <!-- BODY (Contenido Scrollable) -->
                <div class="p-6 overflow-y-auto custom-scrollbar flex-grow space-y-8 text-sm leading-relaxed text-gray-700">
                    
                    <!-- AVISO DE PRIVACIDAD -->
                    <section>
                        <h3 class="text-lg font-bold text-orange-600 mb-4 border-b border-orange-100 pb-2">📄 AVISO DE PRIVACIDAD INTEGRAL</h3>
                        <div class="space-y-4">
                            <p><strong>Última actualización:</strong> 11 de febrero del 2026</p>
                            
                            <h4 class="font-bold text-gray-900 mt-4">1. Identidad y domicilio del responsable</h4>
                            <p class="whitespace-pre-line">CrossoverMX, en lo sucesivo “el Responsable”, es el encargado del tratamiento de los datos personales recabados a través del sistema de gestión de ligas deportivas CrossoverMX, operado por persona física bajo el nombre comercial CrossoverMX.
📍 Domicilio: Ricardo Flores Magón N15, Héroes de la independencia Ecatepec de Morelos Edo Mex. 
📧 Correo electrónico: direccion@crossovermx.com
📞 Teléfono: 55 1140 2976</p>

                            <h4 class="font-bold text-gray-900 mt-4">2. Datos personales que se recaban</h4>
                            <p class="whitespace-pre-line">Para las finalidades señaladas en el presente Aviso de Privacidad, se podrán recabar los siguientes datos personales:
a) Datos de identificación y contacto
•	Nombre completo (encriptado)
•	RFC (encriptado)
•	CURP (encriptado)
•	Sexo
•	Fotografía
•	Número de jugador
•	Equipo, categoría y fuerza deportiva
b) Datos de contacto de emergencia
•	Nombre del contacto (encriptado)
•	Número telefónico (encriptado)
•	Parentesco (encriptado)
c) Datos personales sensibles
•	Tipo de sangre (encriptado)
⚠️ Los datos sensibles serán tratados con medidas de seguridad reforzadas y solo para las finalidades estrictamente necesarias.</p>

                            <h4 class="font-bold text-gray-900 mt-4">3. Finalidades del tratamiento de los datos</h4>
                            <p class="whitespace-pre-line">Finalidades primarias (necesarias para el servicio)
Los datos personales serán utilizados para:
•	Registro y administración de ligas deportivas
•	Identificación única de jugadores dentro del sistema
•	Validación para evitar duplicidad de registros en equipos o ligas
•	Gestión de equipos, categorías y torneos
•	Generación y control de estadísticas deportivas
•	Creación de credenciales de jugador
•	Atención de emergencias dentro del contexto deportivo
•	Operación, soporte y funcionamiento del sistema CrossoverMX
Finalidades secundarias
•	Uso de fotografía para identificación interna y credenciales del sistema
(el uso de fotografía no es obligatorio para el registro)
El titular podrá negar o revocar su consentimiento para finalidades secundarias sin afectar las primarias.</p>

                            <h4 class="font-bold text-gray-900 mt-4">4. Uso de imagen</h4>
                            <p>La fotografía del jugador es opcional y se utiliza únicamente para fines de identificación interna y elaboración de credenciales dentro del sistema. El consentimiento para el uso de imagen puede ser revocado en cualquier momento.</p>

                            <h4 class="font-bold text-gray-900 mt-4">5. Almacenamiento de datos y uso de infraestructura tecnológica (VPS / Hosting)</h4>
                            <p class="whitespace-pre-line">Los datos personales recabados podrán ser almacenados y resguardados en servidores administrados por proveedores de servicios tecnológicos que actúan como encargados del tratamiento de los datos personales, únicamente para la correcta operación del sistema.
Dichos servidores podrán estar ubicados dentro o fuera del territorio nacional, sin que ello implique una transferencia de datos para fines distintos a la prestación del servicio.
En todo momento, el Responsable se asegura de que dichos proveedores mantengan medidas de seguridad adecuadas conforme a la LFPDPPP.</p>

                            <h4 class="font-bold text-gray-900 mt-4">6. Transferencia de datos personales</h4>
                            <p>CrossoverMX no transfiere datos personales a terceros. Los datos permanecen dentro del sistema y son accesibles únicamente por los usuarios autorizados de la liga correspondiente.</p>

                            <h4 class="font-bold text-gray-900 mt-4">7. Datos de menores de edad</h4>
                            <p>Actualmente el sistema no está orientado al registro de menores de edad. En caso de que en el futuro se habilite dicho registro, el tratamiento de los datos se realizará únicamente con el consentimiento expreso del padre, madre o tutor legal, conforme a la legislación aplicable.</p>

                            <h4 class="font-bold text-gray-900 mt-4">8. Medidas de seguridad</h4>
                            <p class="whitespace-pre-line">El Responsable implementa medidas de seguridad administrativas, técnicas y físicas para proteger los datos personales, incluyendo:
•	Encriptación de datos sensibles
•	Control de accesos por roles
•	Autenticación de usuarios
•	Bitácoras de acceso
•	Protección lógica del sistema</p>

                            <h4 class="font-bold text-gray-900 mt-4">9. Derechos ARCO</h4>
                            <p class="whitespace-pre-line">El titular tiene derecho a Acceder, Rectificar, Cancelar u Oponerse al tratamiento de sus datos personales.
Las solicitudes ARCO deberán enviarse a:
📧 direccion@crossovermx.com
La solicitud deberá contener:
•	Nombre del titular
•	Derecho que desea ejercer
•	Descripción clara de la solicitud
•	Medio de contacto para respuesta</p>

                            <h4 class="font-bold text-gray-900 mt-4">10. Uso de cookies</h4>
                            <p>CrossoverMX no utiliza cookies ni tecnologías de rastreo.</p>

                            <h4 class="font-bold text-gray-900 mt-4">11. Relación con las ligas y limitación de responsabilidad</h4>
                            <p>CrossoverMX actúa como proveedor de la plataforma tecnológica. Las ligas, administradores o coaches que registran información son responsables de contar con el consentimiento de los titulares para la carga de datos personales. CrossoverMX no es responsable por el uso indebido de los datos personales realizado por los usuarios del sistema, ni por información proporcionada sin autorización del titular.</p>

                            <h4 class="font-bold text-gray-900 mt-4">12. Cambios al aviso de privacidad</h4>
                            <p>El presente Aviso de Privacidad puede ser modificado en cualquier momento. Las modificaciones serán publicadas a través del sistema CrossoverMX.</p>
                        </div>
                    </section>

                    <hr class="border-gray-200">

                    <!-- TÉRMINOS Y CONDICIONES -->
                    <section>
                        <h3 class="text-lg font-bold text-orange-600 mb-4 border-b border-orange-100 pb-2">📄 TÉRMINOS Y CONDICIONES DE USO</h3>
                        <div class="space-y-4">
                            <p><strong>Última actualización:</strong> 11 de febrero del 2026</p>
                            
                            <h4 class="font-bold text-gray-900 mt-4">1. Aceptación de los Términos</h4>
                            <p>Al acceder, registrarse o utilizar el sistema CrossoverMX, el usuario declara haber leído, entendido y aceptado en su totalidad los presentes Términos y Condiciones, así como el Aviso de Privacidad correspondiente. Si el usuario no está de acuerdo con estos Términos, deberá abstenerse de utilizar el sistema.</p>

                            <h4 class="font-bold text-gray-900 mt-4">2. Identidad del proveedor del servicio</h4>
                            <p>CrossoverMX es un sistema de gestión de ligas deportivas operado por persona física, bajo el nombre comercial CrossoverMX, en lo sucesivo “EL PROVEEDOR”. Correo de contacto: 📧 direccion@crossovermx.com</p>

                            <h4 class="font-bold text-gray-900 mt-4">3. Definiciones</h4>
                            <p class="whitespace-pre-line">Para efectos de estos Términos:
•	Sistema: Plataforma tecnológica CrossoverMX.
•	Usuario: Cualquier persona que accede al sistema.
•	Administrador de Liga: Usuario autorizado para registrar equipos, jugadores y datos.
•	Liga: Organización deportiva que utiliza el sistema.
•	Datos Personales: Información proporcionada por los usuarios conforme al Aviso de Privacidad.</p>

                            <h4 class="font-bold text-gray-900 mt-4">4. Objeto del servicio</h4>
                            <p>CrossoverMX proporciona una plataforma tecnológica para administración de ligas de básquetbol, registro de jugadores y equipos, gestión de torneos, calendarios y estadísticas, y control administrativo y deportivo. CrossoverMX NO es una liga, NO organiza torneos, ni interviene en decisiones deportivas.</p>

                            <h4 class="font-bold text-gray-900 mt-4">5. Registro y uso del sistema</h4>
                            <p>El usuario se compromete a: Proporcionar información veraz, Utilizar el sistema únicamente para fines deportivos y administrativos, Mantener la confidencialidad de sus accesos. Queda estrictamente prohibido: Compartir cuentas, Manipular información ajena, Acceder sin autorización a datos de otras ligas.</p>

                            <h4 class="font-bold text-gray-900 mt-4">6. Responsabilidad sobre los datos personales</h4>
                            <p>Las ligas, administradores y coaches que registran información en el sistema declaran contar con el consentimiento expreso de los titulares, son responsables del uso correcto de los datos personales y asumen cualquier responsabilidad legal derivada del mal uso de la información. CrossoverMX actúa únicamente como proveedor tecnológico.</p>

                            <h4 class="font-bold text-gray-900 mt-4">7. Uso del sistema por menores de edad</h4>
                            <p>Actualmente el sistema no está diseñado para el registro de menores. En caso de que una liga registre menores: Será su responsabilidad contar con el consentimiento del padre, madre o tutor. CrossoverMX no será responsable por registros realizados sin autorización legal.</p>

                            <h4 class="font-bold text-gray-900 mt-4">8. Disponibilidad del servicio</h4>
                            <p>CrossoverMX busca ofrecer un servicio continuo, sin embargo: No garantiza disponibilidad ininterrumpida, Puede haber suspensiones por mantenimiento, fallas técnicas o causas ajenas, No se garantiza que el sistema esté libre de errores.</p>

                            <h4 class="font-bold text-gray-900 mt-4">9. Infraestructura tecnológica y hosting</h4>
                            <p>El sistema se aloja en servidores de terceros (VPS / hosting) que actúan como encargados del tratamiento de datos. CrossoverMX no será responsable por: Fallas del proveedor de hosting, Pérdida de conectividad, Ataques externos inevitables.</p>

                            <h4 class="font-bold text-gray-900 mt-4">10. Limitación de responsabilidad</h4>
                            <p>CrossoverMX NO será responsable por: Lesiones deportivas, Accidentes dentro o fuera de la cancha, Conflictos entre jugadores, equipos o ligas, Decisiones arbitrales, Resultados deportivos, Uso indebido del sistema por terceros. El uso del sistema es bajo responsabilidad del usuario.</p>

                            <h4 class="font-bold text-gray-900 mt-4">11. Propiedad intelectual</h4>
                            <p>Todos los derechos sobre el sistema, marca, diseño, código, nombre y funcionamiento de CrossoverMX son propiedad exclusiva del proveedor. Queda prohibido: Copiar, Modificar, Revender, Descompilar, Realizar ingeniería inversa.</p>

                            <h4 class="font-bold text-gray-900 mt-4">12. Suspensión y cancelación de cuentas</h4>
                            <p>CrossoverMX podrá suspender o cancelar cuentas sin previo aviso cuando: Se incumplan estos Términos, Se detecte uso indebido, Se afecte la seguridad del sistema, Se utilice el sistema para fines ilegales.</p>

                            <h4 class="font-bold text-gray-900 mt-4">13. Modificaciones al servicio y a los términos</h4>
                            <p>CrossoverMX se reserva el derecho de: Modificar funcionalidades, Actualizar estos Términos, Cambiar condiciones de uso. Las modificaciones se notificarán dentro del sistema.</p>

                            <h4 class="font-bold text-gray-900 mt-4">14. Legislación aplicable y jurisdicción</h4>
                            <p>Estos Términos se rigen por las leyes de los Estados Unidos Mexicanos. Cualquier controversia se someterá a los tribunales competentes de México.</p>
                        </div>
                    </section>
                </div>

                <!-- FOOTER (Checkboxes y Botón) -->
                <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex-shrink-0">
                    <form id="termsForm" onsubmit="event.preventDefault();">
                        <div class="space-y-3 mb-6">
                            <!-- CHECKBOX 1: Aviso de Privacidad -->
                            <label class="flex items-start space-x-3 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <!-- 
                                    FIX DEFINITIVO:
                                    1. 'accent-orange-600': Fuerza al navegador a usar naranja en vez de su azul nativo.
                                    2. '!focus:ring-0 !focus:ring-offset-0': Elimina cualquier brillo o anillo restante.
                                    -->
                                    <input type="checkbox" id="check-privacy" 
                                        class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-gray-300 shadow-sm transition-all duration-200
                                            accent-orange-600 
                                            hover:bg-white hover:border-orange-400
                                            checked:bg-orange-600 
                                            checked:border-transparent
                                            !checked:hover:bg-orange-600 !checked:hover:border-transparent
                                            !outline-none !focus:ring-0 !focus:ring-offset-0" 
                                        required>
                                    
                                    <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" viewBox="0 0 14 14" fill="none">
                                        <path d="M3 8L6 11L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-700 select-none group-hover:text-gray-900 pt-0.5">
                                    He leído y acepto el Aviso de Privacidad de CrossoverMX.
                                </span>
                            </label>

                            <!-- CHECKBOX 2: Términos y Condiciones -->
                            <label class="flex items-start space-x-3 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox" id="check-terms" 
                                        class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-gray-300 shadow-sm transition-all duration-200
                                            accent-orange-600 
                                            hover:bg-white hover:border-orange-400
                                            checked:bg-orange-600 
                                            checked:border-transparent
                                            !checked:hover:bg-orange-600 !checked:hover:border-transparent
                                            !outline-none !focus:ring-0 !focus:ring-offset-0" 
                                        required>
                                    
                                    <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" viewBox="0 0 14 14" fill="none">
                                        <path d="M3 8L6 11L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-700 select-none group-hover:text-gray-900 pt-0.5">
                                    He leído y acepto los Términos y Condiciones de CrossoverMX.
                                </span>
                            </label>
                        </div>

                        <!-- Botón de Acción -->
                        <button type="submit" id="btn-accept" disabled 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-gray-400 cursor-not-allowed transition-all duration-200 
                                   disabled:opacity-50 disabled:cursor-not-allowed
                                   enabled:bg-orange-600 enabled:hover:bg-orange-700 enabled:shadow-lg enabled:scale-[1.02]">
                            ACEPTAR Y CONTINUAR
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- LÓGICA JAVASCRIPT PARA EL MODAL -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const privacyCheck = document.getElementById('check-privacy');
                const termsCheck = document.getElementById('check-terms');
                const acceptBtn = document.getElementById('btn-accept');
                const form = document.getElementById('termsForm');
                const backdrop = document.getElementById('termsModalBackdrop');

                // Función para verificar el estado de los checkboxes
                function validateForm() {
                    if (privacyCheck.checked && termsCheck.checked) {
                        acceptBtn.disabled = false;
                        // Estilos habilitados (Tailwind classes 'enabled:...')
                        // El cambio visual se maneja con las clases CSS en el botón,
                        // aquí solo nos aseguramos de que el atributo disabled sea false.
                    } else {
                        acceptBtn.disabled = true;
                    }
                }

                // Event Listeners
                privacyCheck.addEventListener('change', validateForm);
                termsCheck.addEventListener('change', validateForm);

                // Manejo del envío del formulario
                form.addEventListener('submit', function(e) {
                    if (acceptBtn.disabled) return;

                    // Estado de carga
                    const originalText = acceptBtn.innerText;
                    acceptBtn.innerText = 'Procesando...';
                    acceptBtn.disabled = true;

                    // Petición AJAX (Fetch)
                    fetch('<?php echo e(route("terms.accept")); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({}) // No necesitamos enviar datos extra, el backend sabe quién es el usuario
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Éxito: Recargar la página para que el layout desaparezca
                            window.location.reload();
                        } else {
                            throw new Error('Error en el servidor');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hubo un error al procesar tu solicitud. Por favor intenta nuevamente.');
                        acceptBtn.innerText = originalText;
                        acceptBtn.disabled = false;
                        validateForm(); // Restaurar estado correcto del botón
                    });
                });
            });
        </script>
        
        <!-- Estilos adicionales para el scrollbar -->
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 8px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>

    <?php endif; ?>
    <!-- ............................................................................ -->
    <!-- FIN MODAL DE TÉRMINOS Y CONDICIONES                                        -->
    <!-- ............................................................................ -->

    </body>
</html><?php /**PATH C:\xampp\htdocs\sistemaTorneos\resources\views/layouts/app.blade.php ENDPATH**/ ?>