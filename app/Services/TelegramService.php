<?php

namespace App\Services;

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TelegramService
{
    protected ?string $botToken;
    protected ?string $botUsername;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->botUsername = config('services.telegram.bot_username');
    }

    /**
     * Verifica si el bot está configurado con su token.
     */
    public function isConfigured(): bool
    {
        return !empty($this->botToken);
    }

    /**
     * Retorna el username del bot.
     */
    public function getBotUsername(): ?string
    {
        return $this->botUsername;
    }

    /**
     * Genera la URL con deep-link para que el usuario vincule su cuenta.
     */
    public function generateLinkUrl(User $user): ?string
    {
        if (empty($this->botUsername)) {
            return null;
        }

        if (empty($user->telegram_link_token)) {
            $user->telegram_link_token = 'coach_' . $user->id . '_' . Str::random(16);
            $user->save();
        }

        return "https://t.me/{$this->botUsername}?start={$user->telegram_link_token}";
    }

    /**
     * Desvincula la cuenta de Telegram de un usuario.
     */
    public function unlinkUser(User $user): bool
    {
        $user->telegram_chat_id = null;
        $user->telegram_username = null;
        $user->telegram_link_token = null;
        return $user->save();
    }

    /**
     * Envía un mensaje de texto a un chat_id de Telegram.
     */
    public function sendMessage(string|int $chatId, string $text, string $parseMode = 'Markdown'): array
    {
        if (!$this->isConfigured()) {
            Log::warning('TelegramService: Token de bot no configurado.');
            return ['ok' => false, 'description' => 'TELEGRAM_BOT_TOKEN no configurado en el servidor.'];
        }

        try {
            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => true,
            ]);

            $json = $response->json();
            if (!$response->successful() || empty($json['ok'])) {
                Log::error('TelegramService sendMessage falló: ' . ($json['description'] ?? 'Error desconocido'), [
                    'chat_id' => $chatId,
                    'response' => $json
                ]);
            }

            return $json ?? ['ok' => false];
        } catch (\Throwable $e) {
            Log::error('TelegramService Exception en sendMessage: ' . $e->getMessage());
            return ['ok' => false, 'description' => $e->getMessage()];
        }
    }

    /**
     * Notifica a los entrenadores (local y visitante) sobre un partido programado.
     */
    public function notifyGameScheduled(Game $game): array
    {
        $game->loadMissing(['tournament', 'localTeam.coach', 'awayTeam.coach', 'court']);

        $tournamentName = $game->tournament->name ?? 'Torneo';
        $groupName = $game->group_name ?? ($game->category_group ?? null);
        $courtName = $game->court->name ?? 'Cancha por definir';

        // Formato amigable de fecha y hora
        $dateFormatted = 'Fecha por definir';
        $timeFormatted = 'Horario por definir';
        if ($game->date_time) {
            $carbon = \Carbon\Carbon::parse($game->date_time)->locale('es');
            $dateFormatted = ucfirst($carbon->translatedFormat('l, d \d\e F \d\e Y'));
            $timeFormatted = $carbon->format('H:i') . ' hrs';
        }

        $localTeam = $game->localTeam;
        $awayTeam = $game->awayTeam;
        $localCoach = $localTeam->coach ?? null;
        $awayCoach = $awayTeam->coach ?? null;

        $results = [
            'local' => ['sent' => false, 'reason' => null],
            'away' => ['sent' => false, 'reason' => null],
        ];

        // Caso especial: Ambos equipos tienen el mismo entrenador
        if ($localCoach && $awayCoach && $localCoach->id === $awayCoach->id && !empty($localCoach->telegram_chat_id)) {
            $msg = "🏀 *¡Partido programado entre tus equipos!*\n\n"
                 . "🏆 *Torneo:* {$this->escapeMarkdown($tournamentName)}\n"
                 . ($groupName ? "🏷️ *Categoría/Grupo:* {$this->escapeMarkdown($groupName)}\n" : "")
                 . "👥 *Encuentro:* {$this->escapeMarkdown($localTeam->name)} 🆚 {$this->escapeMarkdown($awayTeam->name)}\n"
                 . "📅 *Fecha:* {$dateFormatted}\n"
                 . "⏰ *Horario:* {$timeFormatted}\n"
                 . "📍 *Cancha:* {$this->escapeMarkdown($courtName)}\n\n"
                 . "¡Mucho éxito a ambos equipos! 📋";

            $res = $this->sendMessage($localCoach->telegram_chat_id, $msg);
            $results['local'] = ['sent' => ($res['ok'] ?? false), 'reason' => ($res['description'] ?? 'Enviado')];
            $results['away'] = $results['local'];
            return $results;
        }

        // Notificar Coach Local
        if ($localCoach && !empty($localCoach->telegram_chat_id)) {
            $msgLocal = "🏀 *¡Nuevo partido programado!*\n\n"
                      . "🏆 *Torneo:* {$this->escapeMarkdown($tournamentName)}\n"
                      . ($groupName ? "🏷️ *Categoría/Grupo:* {$this->escapeMarkdown($groupName)}\n" : "")
                      . "👥 *Tu equipo:* {$this->escapeMarkdown($localTeam->name ?? 'Local')}\n"
                      . "🆚 *Rival:* {$this->escapeMarkdown($awayTeam->name ?? 'Visitante')}\n"
                      . "📅 *Fecha:* {$dateFormatted}\n"
                      . "⏰ *Horario:* {$timeFormatted}\n"
                      . "📍 *Cancha:* {$this->escapeMarkdown($courtName)}\n\n"
                      . "¡Mucho éxito en el encuentro! 📋";

            $res = $this->sendMessage($localCoach->telegram_chat_id, $msgLocal);
            $results['local'] = [
                'sent' => ($res['ok'] ?? false),
                'coach' => $localCoach->name,
                'reason' => ($res['description'] ?? 'Enviado')
            ];
        } else {
            $results['local'] = [
                'sent' => false,
                'coach' => $localCoach->name ?? null,
                'reason' => $localCoach ? 'Coach sin Telegram vinculado' : 'Equipo sin coach asignado'
            ];
        }

        // Notificar Coach Visitante
        if ($awayCoach && !empty($awayCoach->telegram_chat_id)) {
            $msgAway = "🏀 *¡Nuevo partido programado!*\n\n"
                     . "🏆 *Torneo:* {$this->escapeMarkdown($tournamentName)}\n"
                     . ($groupName ? "🏷️ *Categoría/Grupo:* {$this->escapeMarkdown($groupName)}\n" : "")
                     . "👥 *Tu equipo:* {$this->escapeMarkdown($awayTeam->name ?? 'Visitante')}\n"
                     . "🆚 *Rival:* {$this->escapeMarkdown($localTeam->name ?? 'Local')}\n"
                     . "📅 *Fecha:* {$dateFormatted}\n"
                     . "⏰ *Horario:* {$timeFormatted}\n"
                     . "📍 *Cancha:* {$this->escapeMarkdown($courtName)}\n\n"
                     . "¡Mucho éxito en el encuentro! 📋";

            $res = $this->sendMessage($awayCoach->telegram_chat_id, $msgAway);
            $results['away'] = [
                'sent' => ($res['ok'] ?? false),
                'coach' => $awayCoach->name,
                'reason' => ($res['description'] ?? 'Enviado')
            ];
        } else {
            $results['away'] = [
                'sent' => false,
                'coach' => $awayCoach->name ?? null,
                'reason' => $awayCoach ? 'Coach sin Telegram vinculado' : 'Equipo sin coach asignado'
            ];
        }

        return $results;
    }

    /**
     * Registra la URL del webhook en los servidores de Telegram.
     */
    public function setWebhook(string $url): array
    {
        if (!$this->isConfigured()) {
            return ['ok' => false, 'description' => 'TELEGRAM_BOT_TOKEN no configurado.'];
        }

        $response = Http::timeout(10)->post("https://api.telegram.org/bot{$this->botToken}/setWebhook", [
            'url' => $url,
            'allowed_updates' => ['message'],
        ]);

        return $response->json() ?? ['ok' => false];
    }

    /**
     * Consulta el estado del webhook en Telegram.
     */
    public function getWebhookInfo(): array
    {
        if (!$this->isConfigured()) {
            return ['ok' => false, 'description' => 'TELEGRAM_BOT_TOKEN no configurado.'];
        }

        $response = Http::timeout(10)->get("https://api.telegram.org/bot{$this->botToken}/getWebhookInfo");
        return $response->json() ?? ['ok' => false];
    }

    /**
     * Elimina el webhook configurado en Telegram.
     */
    public function deleteWebhook(): array
    {
        if (!$this->isConfigured()) {
            return ['ok' => false, 'description' => 'TELEGRAM_BOT_TOKEN no configurado.'];
        }

        $response = Http::timeout(10)->post("https://api.telegram.org/bot{$this->botToken}/deleteWebhook");
        return $response->json() ?? ['ok' => false];
    }

    /**
     * Consulta actualizaciones usando long-polling (útil en entorno local).
     */
    public function getUpdates(int $offset = 0, int $limit = 50, int $timeout = 10): array
    {
        if (!$this->isConfigured()) {
            return ['ok' => false, 'description' => 'TELEGRAM_BOT_TOKEN no configurado.'];
        }

        $response = Http::timeout($timeout + 5)->get("https://api.telegram.org/bot{$this->botToken}/getUpdates", [
            'offset' => $offset,
            'limit' => $limit,
            'timeout' => $timeout,
        ]);

        return $response->json() ?? ['ok' => false];
    }

    /**
     * Procesa un payload de actualización (desde Webhook o getUpdates).
     */
    public function handleUpdate(array $update): bool
    {
        if (!isset($update['message'])) {
            return false;
        }

        $message = $update['message'];
        $chatId = $message['chat']['id'] ?? null;
        $text = trim($message['text'] ?? '');
        $username = $message['from']['username'] ?? null;

        if (!$chatId) {
            return false;
        }

        // Manejar comando /start
        if (str_starts_with($text, '/start')) {
            $parts = explode(' ', $text, 2);
            $token = isset($parts[1]) ? trim($parts[1]) : '';

            if (!empty($token)) {
                $user = User::where('telegram_link_token', $token)->first();

                if ($user) {
                    $user->telegram_chat_id = (string) $chatId;
                    $user->telegram_username = $username;
                    $user->telegram_link_token = null;
                    $user->save();

                    $welcomeMsg = "✅ *¡Cuenta vinculada con éxito!*\n\n"
                                . "Hola *{$user->name}*, tu cuenta de entrenador ha sido conectada con el Sistema de Torneos.\n\n"
                                . "A partir de ahora recibirás aquí automáticamente la información de tus partidos programados (rival, fecha, horario y cancha). 🏀📋";

                    $this->sendMessage($chatId, $welcomeMsg);
                    return true;
                } else {
                    $invalidMsg = "⚠️ *Enlace inválido o expirado*\n\n"
                                . "No pudimos vincular tu cuenta con ese código. Por favor ingresa a tu panel en la plataforma y pulsa nuevamente en *Vincular con Telegram*.";

                    $this->sendMessage($chatId, $invalidMsg);
                    return false;
                }
            }

            // /start sin token
            $existingUser = User::where('telegram_chat_id', (string) $chatId)->first();
            if ($existingUser) {
                $statusMsg = "👋 *¡Hola {$existingUser->name}!*\n\n"
                           . "Tu cuenta ya está vinculada al Sistema de Torneos y activa para recibir notificaciones de tus partidos.";
                $this->sendMessage($chatId, $statusMsg);
            } else {
                $helpMsg = "👋 *¡Bienvenido al Bot de Torneos!*\n\n"
                         . "Para vincular tu cuenta y recibir las alertas de tus partidos, inicia sesión en la plataforma y pulsa en el botón **📲 Vincular con Telegram**.";
                $this->sendMessage($chatId, $helpMsg);
            }

            return true;
        }

        // Manejar comando /desvincular o /stop
        if ($text === '/desvincular' || $text === '/stop') {
            $user = User::where('telegram_chat_id', (string) $chatId)->first();
            if ($user) {
                $this->unlinkUser($user);
                $this->sendMessage($chatId, "Tu cuenta ha sido desvinculada. Ya no recibirás notificaciones aquí.");
                return true;
            }
        }

        return false;
    }

    /**
     * Escapa caracteres problemáticos de Markdown si es necesario.
     */
    protected function escapeMarkdown(string $text): string
    {
        return str_replace(['_', '*', '`', '['], ['\\_', '\\*', '\\`', '\\['], $text);
    }
}
