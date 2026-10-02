<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramPollCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll {--timeout=20 : Segundos de espera por petición (long polling)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Escucha actualizaciones de Telegram en tiempo real (ideal para desarrollo local)';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegramService)
    {
        if (!$telegramService->isConfigured()) {
            $this->error('TELEGRAM_BOT_TOKEN no está configurado en tu archivo .env.');
            return 1;
        }

        $botUsername = $telegramService->getBotUsername();
        $this->info("🤖 Escuchando al bot @{$botUsername}...");
        $this->comment("Presiona CTRL+C para detener el escuchador.");

        // Primero verificamos si hay un webhook activo y lo eliminamos para permitir getUpdates
        $webhookInfo = $telegramService->getWebhookInfo();
        if (!empty($webhookInfo['result']['url'])) {
            $this->warn("Aviso: El bot tenía un webhook registrado ({$webhookInfo['result']['url']}). Eliminándolo temporalmente para activar long-polling...");
            $telegramService->deleteWebhook();
        }

        $offset = 0;
        $timeout = (int) $this->option('timeout');

        while (true) {
            try {
                $response = $telegramService->getUpdates($offset, 50, $timeout);

                if (!empty($response['ok']) && !empty($response['result'])) {
                    foreach ($response['result'] as $update) {
                        $updateId = $update['update_id'];
                        $offset = $updateId + 1;

                        $sender = $update['message']['from']['first_name'] ?? 'Usuario';
                        $text = $update['message']['text'] ?? '';
                        $this->line("[<fg=green>" . date('H:i:s') . "</>] Mensaje de <fg=cyan>{$sender}</>: {$text}");

                        $handled = $telegramService->handleUpdate($update);
                        if ($handled) {
                            $this->info("  ↳ Actualización procesada exitosamente.");
                        }
                    }
                }
            } catch (\Throwable $e) {
                $this->error("Error en polling: " . $e->getMessage());
                sleep(2);
            }

            usleep(500000); // 0.5s pause between polls
        }

        return 0;
    }
}
