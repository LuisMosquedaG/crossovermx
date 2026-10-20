<!-- Modal para Registrar / Modificar Marcador Final y Escanear Hoja de Anotación con IA -->
<div id="setFinalScoreModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm transition-opacity" onclick="closeSetFinalScoreModal()" aria-hidden="true"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-3 sm:p-4">
            <div class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all border border-gray-100 max-h-[92vh] flex flex-col">
                
                <!-- Encabezado fijo -->
                <div class="p-5 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6 text-orange-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <span id="modalFinalScoreTitle">Registrar Marcador Final</span>
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Captura directa del resultado o lectura automática con IA de hoja física</p>
                    </div>
                    <button type="button" onclick="closeSetFinalScoreModal()" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Contenido con scroll independiente -->
                <form id="setFinalScoreForm" onsubmit="submitSetFinalScore(event)" class="overflow-y-auto p-5 space-y-4 flex-1">
                    @csrf
                    <input type="hidden" id="finalScoreGameId" name="game_id">
                    <input type="hidden" id="scoresheetUploadedPath" name="scoresheet_path">

                    <!-- SECCIÓN: Escanear Hoja de Anotación con IA -->
                    <div class="relative overflow-hidden rounded-xl border border-indigo-200 bg-gradient-to-r from-indigo-50/80 via-blue-50/50 to-indigo-50/80 p-4 transition-all">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-indigo-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-wider text-indigo-950 flex items-center gap-1.5">
                                        <span>Lector de Hoja de Anotación</span>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">OCR Local / IA</span>
                                    </h4>
                                    <p class="text-xs text-indigo-800/80 mt-0.5">
                                        Sube o toma una foto del acta a pluma: detectará puntos y faltas por jugador automáticamente en menos de 1 segundo.
                                    </p>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <button type="button" id="btnTriggerScoresheet" onclick="triggerScoresheetUpload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm hover:shadow transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span id="btnScoresheetText">Subir o Tomar Foto</span>
                                </button>
                                <input type="file" id="scoresheetFileInput" accept="image/*,application/pdf" capture="environment" class="hidden" onchange="handleScoresheetFile(event)">
                            </div>
                        </div>

                        <!-- Estado de escaneo en progreso (Spinner) -->
                        <div id="scoresheetScanningStatus" class="hidden mt-3 p-3.5 bg-white/90 rounded-xl border border-indigo-200 shadow-sm flex items-center justify-center gap-3">
                            <svg class="animate-spin h-5 w-5 text-indigo-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-indigo-900 animate-pulse">
                                Analizando acta FIBA con OCR Local... leyendo tanteo corrido y faltas...
                            </span>
                        </div>

                        <!-- Resumen y Enlace del Acta Cargada -->
                        <div id="scoresheetSuccessBadge" class="hidden mt-3 p-2.5 bg-emerald-50 rounded-lg border border-emerald-200 flex items-center justify-between text-xs text-emerald-800">
                            <div class="flex items-center gap-2 truncate">
                                <span>✅</span>
                                <span class="font-medium truncate" id="scoresheetStatusText">Acta analizada con éxito.</span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a id="scoresheetFilePreviewLink" href="#" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-bold underline text-[11px]">
                                    Ver documento ↗
                                </a>
                                <button type="button" onclick="triggerScoresheetUpload()" class="text-gray-500 hover:text-gray-700 text-[11px] underline">
                                    Cambiar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Marcadores Globales de Equipos -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <div class="grid grid-cols-7 gap-3 items-center">
                            <!-- Equipo Local -->
                            <div class="col-span-3 text-center">
                                <label for="modalLocalScore" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1 truncate" id="modalLocalTeamLabel" title="Equipo Local">
                                    Equipo Local
                                </label>
                                <input type="number" name="local_team_score" id="modalLocalScore" min="0" value="0" required
                                    oninput="calculateMatchOutcome()"
                                    class="block w-full text-center text-3xl font-black text-gray-900 rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 py-3">
                                <span class="text-[11px] text-gray-500 mt-1 block font-medium">Local</span>
                            </div>

                            <!-- VS Separador -->
                            <div class="col-span-1 flex flex-col items-center justify-center">
                                <span class="text-xs font-black text-gray-400 bg-white rounded-full px-2 py-1 shadow-sm border border-gray-200">VS</span>
                            </div>

                            <!-- Equipo Visitante -->
                            <div class="col-span-3 text-center">
                                <label for="modalAwayScore" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1 truncate" id="modalAwayTeamLabel" title="Equipo Visitante">
                                    Equipo Visitante
                                </label>
                                <input type="number" name="away_team_score" id="modalAwayScore" min="0" value="0" required
                                    oninput="calculateMatchOutcome()"
                                    class="block w-full text-center text-3xl font-black text-gray-900 rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 py-3">
                                <span class="text-[11px] text-gray-500 mt-1 block font-medium">Visitante</span>
                            </div>
                        </div>
                    </div>

                    <!-- Estado del Resultado (Ganador / Perdedor / Empate) -->
                    <div id="finalScoreOutcomeCard" class="rounded-xl border p-4 transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <div id="finalScoreOutcomeIcon" class="w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0">
                                ⚖️
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-0.5" id="finalScoreOutcomeTitle">
                                    Resultado del Partido
                                </div>
                                <div class="text-sm font-bold text-gray-900 truncate" id="finalScoreOutcomeDesc">
                                    Empate (0 - 0)
                                </div>
                                <div class="text-[11px] text-gray-500 mt-0.5" id="finalScoreOutcomeSub">
                                    Ambos equipos sumarán los puntos asignados por empate.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN EDITABLE DE JUGADORES DETECTADOS (Human-in-the-loop) -->
                    <div id="scoresheetPlayersReviewSection" class="hidden space-y-3">
                        <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-900 flex items-center gap-1.5">
                                    <span>Verificación de Puntos y Faltas por Jugador</span>
                                    <span class="text-xs font-normal text-gray-500">(Editable)</span>
                                </h4>
                                <p class="text-[11px] text-gray-500">Revisa los datos detectados por la IA. Puedes ajustar cualquier número antes de guardar.</p>
                            </div>
                        </div>

                        <!-- Selector de pestaña de equipo (Local / Visitante) -->
                        <div class="flex rounded-lg bg-gray-100 p-1">
                            <button type="button" id="tabBtnLocal" onclick="switchScoresheetTab('local')" class="flex-1 py-1.5 px-3 rounded-md text-xs font-bold transition shadow-sm bg-white text-gray-900">
                                <span id="tabLabelLocal">Equipo Local</span>
                                <span id="tabSumBadgeLocal" class="ml-1.5 px-1.5 py-0.2 rounded-full text-[10px] bg-orange-100 text-orange-800">0 pts</span>
                            </button>
                            <button type="button" id="tabBtnAway" onclick="switchScoresheetTab('away')" class="flex-1 py-1.5 px-3 rounded-md text-xs font-bold transition text-gray-600 hover:text-gray-900">
                                <span id="tabLabelAway">Equipo Visitante</span>
                                <span id="tabSumBadgeAway" class="ml-1.5 px-1.5 py-0.2 rounded-full text-[10px] bg-orange-100 text-orange-800">0 pts</span>
                            </button>
                        </div>

                        <!-- Tabla de Jugadores Locales -->
                        <div id="playersTableLocalContainer" class="space-y-2">
                            <div class="max-h-56 overflow-y-auto rounded-lg border border-gray-200 bg-white">
                                <table class="min-w-full divide-y divide-gray-200 text-xs">
                                    <thead class="bg-gray-50 sticky top-0 z-10 font-bold text-gray-600">
                                        <tr>
                                            <th class="py-2 px-3 text-left w-16">Dorsal</th>
                                            <th class="py-2 px-3 text-left">Jugador</th>
                                            <th class="py-2 px-2 text-center w-24">Puntos</th>
                                            <th class="py-2 px-2 text-center w-20">Faltas</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scoresheetLocalPlayersList" class="divide-y divide-gray-100">
                                        <!-- Filas dinámicas -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-gray-500 px-1">
                                <span>Suma total de puntos asignados: <strong id="localPlayersSumText" class="text-gray-900">0</strong></span>
                                <button type="button" onclick="syncTeamScoreFromPlayers('local')" class="text-orange-600 hover:text-orange-700 font-bold underline">
                                    Actualizar marcador local con esta suma
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Jugadores Visitantes -->
                        <div id="playersTableAwayContainer" class="hidden space-y-2">
                            <div class="max-h-56 overflow-y-auto rounded-lg border border-gray-200 bg-white">
                                <table class="min-w-full divide-y divide-gray-200 text-xs">
                                    <thead class="bg-gray-50 sticky top-0 z-10 font-bold text-gray-600">
                                        <tr>
                                            <th class="py-2 px-3 text-left w-16">Dorsal</th>
                                            <th class="py-2 px-3 text-left">Jugador</th>
                                            <th class="py-2 px-2 text-center w-24">Puntos</th>
                                            <th class="py-2 px-2 text-center w-20">Faltas</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scoresheetAwayPlayersList" class="divide-y divide-gray-100">
                                        <!-- Filas dinámicas -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-gray-500 px-1">
                                <span>Suma total de puntos asignados: <strong id="awayPlayersSumText" class="text-gray-900">0</strong></span>
                                <button type="button" onclick="syncTeamScoreFromPlayers('away')" class="text-orange-600 hover:text-orange-700 font-bold underline">
                                    Actualizar marcador visitante con esta suma
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Nota informativa inferior -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs text-amber-800 flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Al guardar, el partido cambiará automáticamente a <strong>Finalizado</strong>, registrando estadísticas y actualizando la tabla de posiciones en tiempo real.</span>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="bg-gray-50 -mx-5 -mb-5 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-2xl border-t border-gray-100 sticky bottom-0">
                        <button type="submit" id="btnSubmitFinalScore" class="inline-flex justify-center rounded-lg bg-orange-600 px-5 py-2.5 text-sm font-bold text-white shadow hover:bg-orange-700 transition duration-150 ease-in-out">
                            Guardar Marcador Final
                        </button>
                        <button type="button" onclick="closeSetFinalScoreModal()" class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition duration-150 ease-in-out">
                            Cancelar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    let currentFinalScoreLocalName = 'Equipo Local';
    let currentFinalScoreAwayName = 'Equipo Visitante';
    let currentLocalPlayersData = [];
    let currentAwayPlayersData = [];
    let isScoresheetLoaded = false;

    function openSetFinalScoreModal(gameId, localName, awayName, localScore, awayScore) {
        document.getElementById('finalScoreGameId').value = gameId;
        currentFinalScoreLocalName = localName || 'Equipo Local';
        currentFinalScoreAwayName = awayName || 'Equipo Visitante';

        document.getElementById('modalLocalTeamLabel').innerText = localName;
        document.getElementById('modalLocalTeamLabel').title = localName;
        document.getElementById('modalAwayTeamLabel').innerText = awayName;
        document.getElementById('modalAwayTeamLabel').title = awayName;

        document.getElementById('modalLocalScore').value = localScore ?? 0;
        document.getElementById('modalAwayScore').value = awayScore ?? 0;

        const titleEl = document.getElementById('modalFinalScoreTitle');
        if (titleEl) {
            titleEl.innerText = (localScore > 0 || awayScore > 0) ? 'Modificar Marcador Final' : 'Registrar Marcador Final';
        }

        // Resetear estado del escáner
        resetScoresheetScannerState();

        calculateMatchOutcome();

        const modal = document.getElementById('setFinalScoreModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeSetFinalScoreModal() {
        const modal = document.getElementById('setFinalScoreModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        resetScoresheetScannerState();
    }

    function resetScoresheetScannerState() {
        isScoresheetLoaded = false;
        currentLocalPlayersData = [];
        currentAwayPlayersData = [];
        document.getElementById('scoresheetUploadedPath').value = '';
        document.getElementById('scoresheetFileInput').value = '';
        document.getElementById('scoresheetScanningStatus').classList.add('hidden');
        document.getElementById('scoresheetSuccessBadge').classList.add('hidden');
        document.getElementById('scoresheetPlayersReviewSection').classList.add('hidden');
        document.getElementById('scoresheetLocalPlayersList').innerHTML = '';
        document.getElementById('scoresheetAwayPlayersList').innerHTML = '';
        document.getElementById('btnScoresheetText').innerText = 'Subir o Tomar Foto';
    }

    function triggerScoresheetUpload() {
        document.getElementById('scoresheetFileInput').click();
    }

    async function handleScoresheetFile(event) {
        const file = event.target.files[0];
        if (!file) return;

        const gameId = document.getElementById('finalScoreGameId').value;
        if (!gameId) {
            alert('Partido no identificado.');
            return;
        }

        // Validación de tamaño (máx 15MB)
        if (file.size > 15 * 1024 * 1024) {
            alert('El archivo no debe exceder los 15MB.');
            return;
        }

        const scanningStatus = document.getElementById('scoresheetScanningStatus');
        const submitBtn = document.getElementById('btnSubmitFinalScore');
        const triggerBtn = document.getElementById('btnTriggerScoresheet');

        scanningStatus.classList.remove('hidden');
        submitBtn.disabled = true;
        triggerBtn.disabled = true;

        const formData = new FormData();
        formData.append('scoresheet', file);

        try {
            const response = await fetch(`/games/${gameId}/scan-scoresheet`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Actualizar marcadores sugeridos
                if (data.local_team_score !== undefined) {
                    document.getElementById('modalLocalScore').value = data.local_team_score;
                }
                if (data.away_team_score !== undefined) {
                    document.getElementById('modalAwayScore').value = data.away_team_score;
                }
                calculateMatchOutcome();

                // Guardar ruta del archivo
                document.getElementById('scoresheetUploadedPath').value = data.scoresheet_path || '';
                
                // Mostrar badge de éxito y link de preview
                const successBadge = document.getElementById('scoresheetSuccessBadge');
                successBadge.classList.remove('hidden');
                if (data.scoresheet_url) {
                    document.getElementById('scoresheetFilePreviewLink').href = data.scoresheet_url;
                    document.getElementById('scoresheetFilePreviewLink').classList.remove('hidden');
                } else {
                    document.getElementById('scoresheetFilePreviewLink').classList.add('hidden');
                }

                document.getElementById('scoresheetStatusText').innerText = data.confidence_notes || 'Acta analizada con éxito.';
                document.getElementById('btnScoresheetText').innerText = 'Cambiar Foto';

                // Cargar listas de jugadores
                currentLocalPlayersData = data.local_players || [];
                currentAwayPlayersData = data.away_players || [];
                isScoresheetLoaded = true;

                renderPlayersTable('local', currentLocalPlayersData);
                renderPlayersTable('away', currentAwayPlayersData);
                recalculatePlayerTotals();

                // Mostrar sección de revisión
                document.getElementById('tabLabelLocal').innerText = currentFinalScoreLocalName;
                document.getElementById('tabLabelAway').innerText = currentFinalScoreAwayName;
                document.getElementById('scoresheetPlayersReviewSection').classList.remove('hidden');
                switchScoresheetTab('local');
            } else {
                alert(data.message || 'Error al procesar la hoja de anotación.');
            }
        } catch (error) {
            console.error('Error al procesar hoja con Gemini:', error);
            alert('Error de conexión o procesamiento al analizar la hoja.');
        } finally {
            scanningStatus.classList.add('hidden');
            submitBtn.disabled = false;
            triggerBtn.disabled = false;
        }
    }

    function switchScoresheetTab(side) {
        const tabBtnLocal = document.getElementById('tabBtnLocal');
        const tabBtnAway = document.getElementById('tabBtnAway');
        const containerLocal = document.getElementById('playersTableLocalContainer');
        const containerAway = document.getElementById('playersTableAwayContainer');

        if (side === 'local') {
            tabBtnLocal.className = 'flex-1 py-1.5 px-3 rounded-md text-xs font-bold transition shadow-sm bg-white text-gray-900';
            tabBtnAway.className = 'flex-1 py-1.5 px-3 rounded-md text-xs font-bold transition text-gray-600 hover:text-gray-900';
            containerLocal.classList.remove('hidden');
            containerAway.classList.add('hidden');
        } else {
            tabBtnAway.className = 'flex-1 py-1.5 px-3 rounded-md text-xs font-bold transition shadow-sm bg-white text-gray-900';
            tabBtnLocal.className = 'flex-1 py-1.5 px-3 rounded-md text-xs font-bold transition text-gray-600 hover:text-gray-900';
            containerAway.classList.remove('hidden');
            containerLocal.classList.add('hidden');
        }
    }

    function renderPlayersTable(side, players) {
        const tbodyId = (side === 'local') ? 'scoresheetLocalPlayersList' : 'scoresheetAwayPlayersList';
        const tbody = document.getElementById(tbodyId);
        tbody.innerHTML = '';

        if (!players || players.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="py-4 text-center text-gray-400">No hay jugadores registrados en este equipo.</td></tr>`;
            return;
        }

        players.forEach((p, idx) => {
            const tr = document.createElement('tr');
            tr.className = p.detected ? 'bg-indigo-50/20 hover:bg-gray-50' : 'hover:bg-gray-50';

            tr.innerHTML = `
                <td class="py-2 px-3 font-mono font-bold text-gray-700">
                    <span class="inline-block px-1.5 py-0.5 rounded bg-gray-100 text-gray-800 text-[11px]">
                        #${p.jersey_number || '-'}
                    </span>
                </td>
                <td class="py-2 px-3 font-medium text-gray-900">
                    <div class="truncate max-w-[180px] sm:max-w-xs" title="${p.player_name}">
                        ${p.player_name}
                    </div>
                </td>
                <td class="py-1.5 px-2 text-center">
                    <input type="number" min="0" value="${p.points || 0}" 
                        data-player-id="${p.player_id}" 
                        data-side="${side}" 
                        class="player-points-input w-20 text-center font-bold rounded-md border-gray-300 text-xs py-1 px-1.5 focus:border-orange-500 focus:ring-orange-500"
                        oninput="recalculatePlayerTotals()">
                </td>
                <td class="py-1.5 px-2 text-center">
                    <input type="number" min="0" max="5" value="${p.fouls || 0}" 
                        data-player-id="${p.player_id}" 
                        data-side="${side}" 
                        class="player-fouls-input w-16 text-center font-bold rounded-md border-gray-300 text-xs py-1 px-1.5 focus:border-orange-500 focus:ring-orange-500">
                </td>
            `;

            tbody.appendChild(tr);
        });
    }

    function recalculatePlayerTotals() {
        let localSum = 0;
        let awaySum = 0;

        document.querySelectorAll('.player-points-input').forEach(input => {
            const val = parseInt(input.value, 10) || 0;
            const side = input.getAttribute('data-side');
            if (side === 'local') localSum += val;
            if (side === 'away') awaySum += val;
        });

        const localSumEl = document.getElementById('localPlayersSumText');
        const awaySumEl = document.getElementById('awayPlayersSumText');
        const localBadge = document.getElementById('tabSumBadgeLocal');
        const awayBadge = document.getElementById('tabSumBadgeAway');

        if (localSumEl) localSumEl.innerText = localSum;
        if (awaySumEl) awaySumEl.innerText = awaySum;
        if (localBadge) localBadge.innerText = `${localSum} pts`;
        if (awayBadge) awayBadge.innerText = `${awaySum} pts`;
    }

    function syncTeamScoreFromPlayers(side) {
        if (side === 'local') {
            const sum = parseInt(document.getElementById('localPlayersSumText').innerText, 10) || 0;
            document.getElementById('modalLocalScore').value = sum;
        } else {
            const sum = parseInt(document.getElementById('awayPlayersSumText').innerText, 10) || 0;
            document.getElementById('modalAwayScore').value = sum;
        }
        calculateMatchOutcome();
    }

    function calculateMatchOutcome() {
        const localScore = parseInt(document.getElementById('modalLocalScore').value, 10) || 0;
        const awayScore = parseInt(document.getElementById('modalAwayScore').value, 10) || 0;

        const card = document.getElementById('finalScoreOutcomeCard');
        const icon = document.getElementById('finalScoreOutcomeIcon');
        const title = document.getElementById('finalScoreOutcomeTitle');
        const desc = document.getElementById('finalScoreOutcomeDesc');
        const sub = document.getElementById('finalScoreOutcomeSub');

        const diff = Math.abs(localScore - awayScore);

        if (localScore > awayScore) {
            card.className = 'rounded-xl border border-emerald-200 bg-emerald-50 p-4 transition-all duration-200';
            icon.className = 'w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0 bg-emerald-100 text-emerald-700';
            icon.innerHTML = '🏆';
            title.innerText = 'Victoria Local';
            title.className = 'text-xs font-bold uppercase tracking-wider text-emerald-800 mb-0.5';
            desc.innerHTML = `<span class="text-emerald-900 font-extrabold">${currentFinalScoreLocalName}</span> gana por <span class="text-emerald-700 font-black">+${diff} pts</span>`;
            sub.innerHTML = `Perdedor: <span class="font-medium text-gray-700">${currentFinalScoreAwayName}</span>`;
        } else if (awayScore > localScore) {
            card.className = 'rounded-xl border border-emerald-200 bg-emerald-50 p-4 transition-all duration-200';
            icon.className = 'w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0 bg-emerald-100 text-emerald-700';
            icon.innerHTML = '🏆';
            title.innerText = 'Victoria Visitante';
            title.className = 'text-xs font-bold uppercase tracking-wider text-emerald-800 mb-0.5';
            desc.innerHTML = `<span class="text-emerald-900 font-extrabold">${currentFinalScoreAwayName}</span> gana por <span class="text-emerald-700 font-black">+${diff} pts</span>`;
            sub.innerHTML = `Perdedor: <span class="font-medium text-gray-700">${currentFinalScoreLocalName}</span>`;
        } else {
            card.className = 'rounded-xl border border-amber-200 bg-amber-50 p-4 transition-all duration-200';
            icon.className = 'w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0 bg-amber-100 text-amber-700';
            icon.innerHTML = '⚖️';
            title.innerText = 'Empate';
            title.className = 'text-xs font-bold uppercase tracking-wider text-amber-800 mb-0.5';
            desc.innerHTML = `Empate a <span class="font-black text-amber-900">${localScore}</span> puntos`;
            sub.innerHTML = `${currentFinalScoreLocalName} y ${currentFinalScoreAwayName} se reparten puntos de empate.`;
        }
    }

    async function submitSetFinalScore(event) {
        event.preventDefault();
        const gameId = document.getElementById('finalScoreGameId').value;
        const localScore = document.getElementById('modalLocalScore').value;
        const awayScore = document.getElementById('modalAwayScore').value;
        const scoresheetPath = document.getElementById('scoresheetUploadedPath').value;

        if (!gameId) {
            alert('ID de partido no válido.');
            return;
        }

        // Recopilar estadísticas de jugadores si la sección de acta está cargada
        const playerStats = [];
        if (isScoresheetLoaded) {
            const pointsInputs = document.querySelectorAll('.player-points-input');
            const foulsInputs = document.querySelectorAll('.player-fouls-input');

            pointsInputs.forEach((ptInput, idx) => {
                const playerId = parseInt(ptInput.getAttribute('data-player-id'), 10);
                const side = ptInput.getAttribute('data-side');
                const points = parseInt(ptInput.value, 10) || 0;
                
                // Buscar input de faltas correspondiente al mismo jugador
                const foulInput = document.querySelector(`.player-fouls-input[data-player-id="${playerId}"][data-side="${side}"]`);
                const fouls = foulInput ? (parseInt(foulInput.value, 10) || 0) : 0;

                if (playerId > 0) {
                    playerStats.push({
                        player_id: playerId,
                        team_side: side,
                        points: points,
                        fouls: fouls
                    });
                }
            });
        }

        const btn = document.getElementById('btnSubmitFinalScore');
        const originalText = btn.innerText;
        btn.innerText = 'Guardando marcador...';
        btn.disabled = true;

        try {
            const url = `/games/${gameId}/set-final-score`;
            const payload = {
                local_team_score: localScore,
                away_team_score: awayScore,
                scoresheet_path: scoresheetPath || null,
                player_stats: playerStats.length > 0 ? playerStats : null
            };

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                closeSetFinalScoreModal();
                alert(data.message || 'Marcador final guardado exitosamente.');
                window.location.reload();
            } else {
                alert(data.message || 'Error al guardar el marcador final.');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        } catch (error) {
            console.error('Error al guardar marcador final:', error);
            alert('Error de conexión al guardar el marcador final.');
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }
</script>
