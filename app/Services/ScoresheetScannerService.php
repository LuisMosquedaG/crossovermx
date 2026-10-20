<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScoresheetScannerService
{
    /**
     * Modelos a intentar en caso de saturación o alta demanda (failover automático).
     */
    protected array $fallbackModels = [
        'gemini-3.8-flash',
        'gemini-3.7-flash',
        'gemini-3.6-flash',
        'gemini-flash-latest',
        'gemini-3-flash-preview',
    ];

    /**
     * Modelos de OpenRouter con visión a intentar (gratuitos).
     */
    protected array $openRouterFallbackModels = [
        'google/gemma-4-31b-it:free',
        'google/gemma-4-26b-a4b-it:free',
        'nvidia/nemotron-3.5-super-vl:free',
        'minimax/minimax-m3.1-flash:free',
        'dots-studio/dots-3-note-preview:free',
    ];

    /**
     * Escanea una hoja de anotación (imagen o PDF) y extrae los datos del partido.
     *
     * @param UploadedFile|string $file
     * @param Game $game
     * @return array
     * @throws \Exception
     */
    public function scan($file, Game $game): array
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(180);
        }
        @ini_set('max_execution_time', '180');

        // Obtener datos binarios y tipo MIME
        if ($file instanceof UploadedFile) {
            $filePath = $file->getRealPath();
            $mimeType = $file->getMimeType() ?: 'image/jpeg';
        } elseif (is_string($file) && file_exists($file)) {
            $filePath = $file;
            $mimeType = mime_content_type($file) ?: 'image/jpeg';
        } else {
            throw new \Exception('Archivo de hoja de anotación inválido o no encontrado.');
        }

        // Optimizar imagen si supera resolución recomendada para acelerar transmisión e inferencia
        $base64Data = $this->prepareOptimizedImageBase64($filePath, $mimeType);

        // Normalizar tipos MIME aceptados
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/heic', 'image/heif', 'application/pdf'];
        if (!in_array($mimeType, $allowedMimes)) {
            // Fallback para tipos de imagen comunes
            if (str_contains($mimeType, 'pdf')) {
                $mimeType = 'application/pdf';
            } else {
                $mimeType = 'image/jpeg';
            }
        }

        // Cargar información de equipos y jugadores
        $game->loadMissing(['localTeam.players', 'awayTeam.players', 'tournament']);

        $localTeamName = $game->localTeam?->name ?? 'Equipo Local';
        $awayTeamName = $game->awayTeam?->name ?? 'Equipo Visitante';

        $localPlayers = $game->localTeam?->players ?? collect();
        $awayPlayers = $game->awayTeam?->players ?? collect();

        // Construir la lista de referencia del roster para guiar a la IA
        $localRosterText = $localPlayers->map(function (Player $p) {
            return "- ID: {$p->id} | Dorsal/Número: " . ($p->number ?? 'S/N') . " | Nombre: {$p->name}";
        })->implode("\n");

        $awayRosterText = $awayPlayers->map(function (Player $p) {
            return "- ID: {$p->id} | Dorsal/Número: " . ($p->number ?? 'S/N') . " | Nombre: {$p->name}";
        })->implode("\n");

        $prompt = <<<PROMPT
Eres un oficial de mesa y anotador deportivo profesional de baloncesto. Tu objetivo es procesar EXCLUSIVAMENTE la SECCIÓN 3 de esta acta de partido oficial FIBA / hoja de anotación manuscrita.
IMPORTANTE: IGNORA LAS SECCIONES 1, 2, 4 Y 5. En particular, IGNORA por completo el recuadro inferior de "Marcador Final" o "Marcador por periodos" de la Sección 4, ya que frecuentemente contiene errores humanos de llenado o anotaciones de default. Toda la información de puntos y faltas debe provenir ÚNICA Y EXCLUSIVAMENTE de la SECCIÓN 3.

DESCRIPCIÓN DE LA SECCIÓN 3:
1. SECCIÓN 3 - LADO IZQUIERDO (ROSTERS Y FALTAS):
   - Arriba está el EQUIPO A y abajo el EQUIPO B.
   - Cada equipo tiene su tabla de jugadores con columnas: [Nombre del jugador], [Número de camiseta #] y [Casillas de faltas 1, 2, 3, 4, 5].
   - REGLA DE VINCULACIÓN: Para cada jugador registrado en la base de datos (proporcionados más abajo), búscalo en la lista de su respectivo equipo en la hoja. El NÚMERO (#) de camiseta debe ser EXACTO, y al menos 1 nombre o apellido debe coincidir (ej: "samuel" con "Castellanos Samuel" #14, "luis" con "Contreras Luis" #23 o #3, etc.).
   - FALTAS PERSONALES: Cuenta cuántas casillas de faltas (marcas P, P1, P2, T, U, D, o marcas X) están llenadas en la fila de ese jugador (número entero entre 0 y 5).

2. SECCIÓN 3 - LADO DERECHO (PUNTUACIÓN ARRASTRADA / TANTEO CORRIDO):
   - Contiene 4 columnas dobles de tanteo corrido bajo los encabezados A y B.
   - Cada columna doble se divide exactamente en 4 SUB-COLUMNAS:
     * [Sub-columna 1: Número de jugador del EQUIPO A]
     * [Sub-columna 2: Puntos correlativos impresos del EQUIPO A (1 al 40)]
     * [Sub-columna 3: Puntos correlativos impresos del EQUIPO B (1 al 40)]
     * [Sub-columna 4: Número de jugador del EQUIPO B]
   - CÓMO SE REGISTRAN Y CALCULAN LOS PUNTOS DE CADA EQUIPO:
     * CANASTAS DE EQUIPO A: El dorsal del anotador está escrito en la SUB-COLUMNA 1 (a la extrema izquierda). Los puntos acumulados alcanzados están tachados/marcados en la Sub-columna 2.
     * CANASTAS DE EQUIPO B: El dorsal del anotador está escrito en la SUB-COLUMNA 4 (a la extrema derecha). Los puntos acumulados alcanzados están tachados/marcados en la Sub-columna 3.
     * NOTA SOBRE PERIODOS Y TINTAS: En las actas FIBA, el árbitro alterna el color de tinta (azul/negro en cuartos 1 y 3, y rojo en cuartos 2 y 4). DEBES LEER TODAS LAS FILAS EN AMBAS TINTAS hasta la línea horizontal gruesa de cierre de partido.
     * Puntos por canasta: Equivalen a la diferencia entre el punto alcanzado en esa fila y el punto anterior del mismo equipo.
     * Para cada jugador de ambos equipos, debes sumar todas las canastas donde aparezca su número de camiseta en su respectiva sub-columna (Sub-col 1 para A, Sub-col 4 para B).
     * Si un jugador no tiene canastas en el tanteo corrido, sus puntos son 0.
   - MARCADOR TOTAL DE CADA EQUIPO:
     * El marcador del Equipo A es el punto correlativo más alto alcanzado en la Sub-columna 2 al cierre.
     * El marcador del Equipo B es el punto correlativo más alto alcanzado en la Sub-columna 3 al cierre.
     * La suma de puntos individuales de cada equipo debe coincidir con este total. NUNCA asumas un marcador de 0 ni un empate inventado.

JUGADORES REGISTRADOS EN BASE DE DATOS:
--- EQUIPO LOCAL ({$localTeamName}) ---
{$localRosterText}

--- EQUIPO VISITANTE ({$awayTeamName}) ---
{$awayRosterText}

DEVUELVE ÚNICAMENTE UN JSON CON ESTA ESTRUCTURA EXACTA:
{
  "matched_local_team": "Nombre en acta",
  "matched_away_team": "Nombre en acta",
  "local_team_score": 0,
  "away_team_score": 0,
  "confidence_notes": "Explicación breve de la extracción de puntos y faltas de la sección 3",
  "local_players": [
    {
      "player_id": 123,
      "player_name": "Nombre en sistema",
      "jersey_number": "5",
      "points": 0,
      "fouls": 0
    }
  ],
  "away_players": [
    {
      "player_id": 456,
      "player_name": "Nombre en sistema",
      "jersey_number": "1",
      "points": 0,
      "fouls": 0
    }
  ]
}
PROMPT;

        $provider = env('SCORESHEET_PROVIDER', 'local_ocr');
        $fallbackToCloud = filter_var(env('SCORESHEET_FALLBACK_TO_AI', false), FILTER_VALIDATE_BOOLEAN);

        // 1. Intentar primero con el Motor de OCR Local (si está configurado o como predeterminado)
        if ($provider === 'local_ocr') {
            try {
                return $this->scanWithLocalOcr($filePath, $game, $localPlayers, $awayPlayers);
            } catch (\Throwable $e) {
                Log::warning("OCR Local falló: {$e->getMessage()}");

                // Si no se activó explícitamente el fallback o no hay API keys configuradas,
                // relanzamos el error de inmediato para un diagnóstico rápido y claro
                if (!$fallbackToCloud) {
                    throw new \Exception($e->getMessage());
                }

                $openRouterKey = config('services.openrouter.api_key', env('OPENROUTER_API_KEY'));
                $geminiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
                if (empty($openRouterKey) && empty($geminiKey)) {
                    throw new \Exception($e->getMessage());
                }

                Log::info("Recurriendo a respaldo en nube...");
            }
        }

        // 2. Si OCR local no está activo o falló, intentar con OpenRouter
        $openRouterKey = config('services.openrouter.api_key', env('OPENROUTER_API_KEY'));
        if (($provider === 'openrouter' || $provider === 'local_ocr') && !empty($openRouterKey)) {
            try {
                return $this->scanWithOpenRouter($base64Data, $mimeType, $prompt, $localPlayers, $awayPlayers);
            } catch (\Throwable $e) {
                Log::warning("OpenRouter falló ({$e->getMessage()}), recurriendo a Gemini...");
            }
        }

        return $this->scanWithGemini($base64Data, $mimeType, $prompt, $localPlayers, $awayPlayers);
    }

    /**
     * Detecta el binario de Python adecuado para el entorno actual (Windows o Linux/VPS).
     */
    protected function getPythonBinary(): string
    {
        $configured = env('PYTHON_BIN');
        if (!empty($configured)) {
            return $configured;
        }

        // Si existe un entorno virtual local en el proyecto, priorizarlo
        $venvCandidates = [
            base_path('venv/bin/python3'),
            base_path('venv/bin/python'),
            base_path('.venv/bin/python3'),
            base_path('.venv/bin/python'),
            base_path('venv/Scripts/python.exe'),
            base_path('.venv/Scripts/python.exe'),
        ];
        foreach ($venvCandidates as $venvPath) {
            if (file_exists($venvPath) && is_executable($venvPath)) {
                return $venvPath;
            }
        }

        if (PHP_OS_FAMILY === 'Windows') {
            return 'python';
        }

        // En Linux / VPS probar ejecutables comunes en PATH
        $candidates = ['python3', 'python', '/usr/bin/python3', '/usr/local/bin/python3', '/usr/bin/python'];
        foreach ($candidates as $bin) {
            $path = @shell_exec("command -v {$bin} 2>/dev/null");
            if (!empty($path) && trim($path) !== '') {
                return trim($path);
            }
        }

        return 'python3';
    }

    /**
     * Procesa la hoja utilizando el motor de OCR local y determinístico (Python).
     */
    protected function scanWithLocalOcr(string $filePath, Game $game, $localPlayers, $awayPlayers): array
    {
        $pythonBin = $this->getPythonBinary();
        $scriptPath = base_path('scripts/ocr/scan_scoresheet.py');

        if (!file_exists($scriptPath)) {
            throw new \Exception("Script de OCR local no encontrado en: {$scriptPath}");
        }

        $localRosterData = $localPlayers->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'number' => $p->number,
        ])->values()->toArray();

        $awayRosterData = $awayPlayers->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'number' => $p->number,
        ])->values()->toArray();

        $tempLocalRoster = tempnam(sys_get_temp_dir(), 'roster_loc_');
        $tempAwayRoster = tempnam(sys_get_temp_dir(), 'roster_away_');

        file_put_contents($tempLocalRoster, json_encode($localRosterData, JSON_UNESCAPED_UNICODE));
        file_put_contents($tempAwayRoster, json_encode($awayRosterData, JSON_UNESCAPED_UNICODE));

        $localName = escapeshellarg($game->localTeam?->name ?? 'Equipo Local');
        $awayName = escapeshellarg($game->awayTeam?->name ?? 'Equipo Visitante');
        $escapedImagePath = escapeshellarg($filePath);
        $escapedLocalRoster = escapeshellarg($tempLocalRoster);
        $escapedAwayRoster = escapeshellarg($tempAwayRoster);
        $escapedScript = escapeshellarg($scriptPath);
        $escapedPythonBin = escapeshellcmd($pythonBin);

        $cmd = "{$escapedPythonBin} {$escapedScript} --image {$escapedImagePath} --local-roster {$escapedLocalRoster} --away-roster {$escapedAwayRoster} --local-name {$localName} --away-name {$awayName} 2>&1";

        Log::info("Ejecutando OCR Local: {$cmd}");
        $output = shell_exec($cmd);

        @unlink($tempLocalRoster);
        @unlink($tempAwayRoster);

        if (!$output) {
            throw new \Exception("El motor de OCR local no generó ninguna salida al ejecutar '{$escapedPythonBin}'. Verifica que Python esté instalado y sea accesible.");
        }

        $output = trim($output);
        if (!mb_check_encoding($output, 'UTF-8')) {
            $output = mb_convert_encoding($output, 'UTF-8', 'ISO-8859-1, WINDOWS-1252, CP850');
        }

        // Buscar bloque JSON en la salida
        if (preg_match('/\{[\s\S]*\}/', $output, $matches)) {
            $parsed = json_decode($matches[0], true);
        } else {
            $parsed = json_decode($output, true);
        }

        if (!is_array($parsed) || empty($parsed['success'])) {
            $jsonErr = json_last_error_msg();
            $errDetail = is_array($parsed) && isset($parsed['error']) ? $parsed['error'] : "({$jsonErr}) " . substr($output, 0, 500);
            throw new \Exception("{$errDetail}");
        }

        return $this->mergeWithFullRoster($parsed, $localPlayers, $awayPlayers);
    }

    /**
     * Procesa la hoja utilizando OpenRouter API (modelos de visión gratuitos o de catálogo).
     */
    protected function scanWithOpenRouter(string $base64Data, string $mimeType, string $prompt, $localPlayers, $awayPlayers): array
    {
        $apiKey = config('services.openrouter.api_key', env('OPENROUTER_API_KEY'));
        $primaryModel = config('services.openrouter.model', env('OPENROUTER_MODEL', 'google/gemma-4-31b-it:free'));

        $modelsToTry = array_unique(array_merge(
            [$primaryModel],
            $this->openRouterFallbackModels
        ));

        $lastException = null;

        foreach ($modelsToTry as $model) {
            try {
                $endpoint = 'https://openrouter.ai/api/v1/chat/completions';

                $response = Http::timeout(35)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'HTTP-Referer' => config('app.url', 'http://localhost:8000'),
                        'X-Title' => 'Sistema Torneos',
                        'Content-Type' => 'application/json',
                    ])
                    ->post($endpoint, [
                        'model' => $model,
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => [
                                    ['type' => 'text', 'text' => $prompt],
                                    [
                                        'type' => 'image_url',
                                        'image_url' => [
                                            'url' => "data:{$mimeType};base64,{$base64Data}",
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'temperature' => 0.1,
                    ]);

                if ($response->successful()) {
                    $jsonResult = $response->json();
                    $rawText = $jsonResult['choices'][0]['message']['content'] ?? null;

                    if (!$rawText) {
                        throw new \Exception("La respuesta de OpenRouter ({$model}) no contiene texto.");
                    }

                    // Extraer JSON si el modelo lo envolvió en bloques markdown
                    if (preg_match('/\{[\s\S]*\}/', $rawText, $matches)) {
                        $parsed = json_decode($matches[0], true);
                    } else {
                        $parsed = json_decode($rawText, true);
                    }

                    if (!is_array($parsed)) {
                        throw new \Exception("Error al decodificar la respuesta JSON de OpenRouter ({$model}).");
                    }

                    return $this->mergeWithFullRoster($parsed, $localPlayers, $awayPlayers);
                }

                $status = $response->status();
                $errBody = $response->body();
                Log::warning("OpenRouter model {$model} falló con status {$status}: {$errBody}");

                if (in_array($status, [429, 503, 500, 404])) {
                    continue;
                }

                throw new \Exception("Error de OpenRouter API ({$status}): " . ($response->json('error.message') ?? $errBody));
            } catch (\Exception $e) {
                $lastException = $e;
                Log::warning("Excepción llamando a OpenRouter {$model}: " . $e->getMessage());
            }
        }

        // Si OpenRouter falla y Gemini está configurado, reintentar con Gemini
        if (!empty(config('services.gemini.api_key', env('GEMINI_API_KEY')))) {
            Log::info('OpenRouter agotado o no disponible, intentando con Gemini como respaldo...');
            return $this->scanWithGemini($base64Data, $mimeType, $prompt, $localPlayers, $awayPlayers);
        }

        throw new \Exception("No fue posible procesar el acta con OpenRouter: " . ($lastException ? $lastException->getMessage() : 'Error desconocido'));
    }

    /**
     * Procesa la hoja utilizando Google Gemini API.
     */
    protected function scanWithGemini(string $base64Data, string $mimeType, string $prompt, $localPlayers, $awayPlayers): array
    {
        $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        if (empty($apiKey)) {
            throw new \Exception('La clave GEMINI_API_KEY no está configurada en el sistema.');
        }

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $base64Data,
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'temperature' => 0.1,
            ]
        ];

        // Intentar con el modelo configurado y luego con fallbacks si es necesario
        $modelsToTry = array_unique(array_merge(
            [config('services.gemini.model', 'gemini-3.8-flash')],
            $this->fallbackModels
        ));

        $lastException = null;

        foreach ($modelsToTry as $model) {
            try {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

                $response = Http::timeout(45)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($endpoint, $payload);

                if ($response->successful()) {
                    $jsonResult = $response->json();
                    $rawText = $jsonResult['candidates'][0]['content']['parts'][0]['text'] ?? null;

                    if (!$rawText) {
                        throw new \Exception("La respuesta de Gemini no contiene datos legibles.");
                    }

                    $parsed = json_decode($rawText, true);
                    if (!is_array($parsed)) {
                        throw new \Exception("Error al decodificar la respuesta JSON de Gemini.");
                    }

                    // Completar el roster con todos los jugadores registrados (para que el usuario vea la lista completa)
                    return $this->mergeWithFullRoster($parsed, $localPlayers, $awayPlayers);
                }

                $status = $response->status();
                $errBody = $response->body();
                Log::warning("Gemini model {$model} falló con status {$status}: {$errBody}");

                // Si es error de cuota o sobrecarga (429, 503, 500), intentamos el siguiente modelo
                if (in_array($status, [429, 503, 500, 404])) {
                    continue;
                }

                throw new \Exception("Error de Gemini API ({$status}): " . ($response->json('error.message') ?? $errBody));
            } catch (\Exception $e) {
                $lastException = $e;
                Log::warning("Excepción llamando a Gemini {$model}: " . $e->getMessage());
                // Continuar intentando con el siguiente modelo
            }
        }

        throw new \Exception("No fue posible procesar el acta con Gemini: " . ($lastException ? $lastException->getMessage() : 'Error desconocido'));
    }

    /**
     * Asegura que todos los jugadores de ambos equipos estén presentes en el array devuelto
     * para que la interfaz muestre el roster completo editable.
     */
    protected function mergeWithFullRoster(array $parsed, $localPlayers, $awayPlayers): array
    {
        $detectedLocal = collect($parsed['local_players'] ?? []);
        $detectedAway = collect($parsed['away_players'] ?? []);

        // Mapear jugadores locales
        $fullLocal = $localPlayers->map(function (Player $p) use ($detectedLocal) {
            $found = $detectedLocal->first(function ($item) use ($p) {
                return (isset($item['player_id']) && $item['player_id'] == $p->id)
                    || (isset($item['jersey_number']) && $p->number && trim((string)$item['jersey_number']) == trim((string)$p->number));
            });

            return [
                'player_id' => $p->id,
                'player_name' => $p->name,
                'jersey_number' => $p->number ?? '',
                'points' => (int) ($found['points'] ?? 0),
                'fouls' => (int) ($found['fouls'] ?? 0),
                'detected' => (bool) $found,
            ];
        })->values()->toArray();

        // Mapear jugadores visitantes
        $fullAway = $awayPlayers->map(function (Player $p) use ($detectedAway) {
            $found = $detectedAway->first(function ($item) use ($p) {
                return (isset($item['player_id']) && $item['player_id'] == $p->id)
                    || (isset($item['jersey_number']) && $p->number && trim((string)$item['jersey_number']) == trim((string)$p->number));
            });

            return [
                'player_id' => $p->id,
                'player_name' => $p->name,
                'jersey_number' => $p->number ?? '',
                'points' => (int) ($found['points'] ?? 0),
                'fouls' => (int) ($found['fouls'] ?? 0),
                'detected' => (bool) $found,
            ];
        })->values()->toArray();

        return [
            'success' => true,
            'matched_local_team' => $parsed['matched_local_team'] ?? null,
            'matched_away_team' => $parsed['matched_away_team'] ?? null,
            'local_team_score' => (int) ($parsed['local_team_score'] ?? 0),
            'away_team_score' => (int) ($parsed['away_team_score'] ?? 0),
            'confidence_notes' => $parsed['confidence_notes'] ?? 'Hoja procesada exitosamente.',
            'local_players' => $fullLocal,
            'away_players' => $fullAway,
        ];
    }

    /**
     * Optimiza la imagen si supera los 1600px para acelerar la subida e inferencia de Gemini.
     */
    protected function prepareOptimizedImageBase64(string $filePath, string &$mimeType): string
    {
        if (str_contains($mimeType, 'pdf')) {
            return base64_encode(file_get_contents($filePath));
        }

        if (extension_loaded('gd')) {
            try {
                $imageInfo = @getimagesize($filePath);
                if ($imageInfo) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];
                    $maxDim = 1600;

                    if ($width > $maxDim || $height > $maxDim) {
                        $ratio = min($maxDim / $width, $maxDim / $height);
                        $newWidth = (int) round($width * $ratio);
                        $newHeight = (int) round($height * $ratio);

                        $srcImg = null;
                        switch ($imageInfo[2]) {
                            case IMAGETYPE_JPEG:
                                $srcImg = @imagecreatefromjpeg($filePath);
                                break;
                            case IMAGETYPE_PNG:
                                $srcImg = @imagecreatefrompng($filePath);
                                break;
                            case IMAGETYPE_WEBP:
                                if (function_exists('imagecreatefromwebp')) {
                                    $srcImg = @imagecreatefromwebp($filePath);
                                }
                                break;
                        }

                        if ($srcImg) {
                            $resized = imagescale($srcImg, $newWidth, $newHeight);
                            imagedestroy($srcImg);

                            if ($resized) {
                                ob_start();
                                imagejpeg($resized, null, 85);
                                $data = ob_get_clean();
                                imagedestroy($resized);

                                $mimeType = 'image/jpeg';
                                return base64_encode($data);
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('No se pudo optimizar la imagen con GD: ' . $e->getMessage());
            }
        }

        return base64_encode(file_get_contents($filePath));
    }
}
