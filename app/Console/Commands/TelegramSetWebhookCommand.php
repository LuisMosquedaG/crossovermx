<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramSetWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:webhook {action=status : Acción a realizar: status, set, delete} {url? : URL del webhook para la acción set}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Administra el webhook del bot de Telegram (status, set, delete)';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegramService)
    {
        if (!$telegramService->isConfigured()) {
            $this->error('TELEGRAM_BOT_TOKEN no está configurado en tu archivo .env.');
            return 1;
        }

        $action = $this->argument('action');

        switch ($action) {
            case 'set':
                $url = $this->argument('url');
                if (!$url) {
                    $url = url('/telegram/webhook');
                    if (!str_starts_with($url, 'https://')) {
                        $this->warn("Aviso: Telegram requiere HTTPS para los webhooks. La URL generada es: {$url}");
                        if (!$this->confirm('¿Deseas continuar con esta URL?', false)) {
                            return 1;
                        }
                    }
                }

                $this->info("Registrando webhook en Telegram con URL: {$url}...");
                $res = $telegramService->setWebhook($url);
                if (!empty($res['ok'])) {
                    $this->info('¡Webhook configurado con éxito en Telegram!');
                } else {
                    $this->error('Error al configurar webhook: ' . ($res['description'] ?? 'Error desconocido'));
                }
                break;

            case 'delete':
                $this->info('Eliminando webhook de Telegram...');
                $res = $telegramService->deleteWebhook();
                if (!empty($res['ok'])) {
                    $this->info('Webhook eliminado correctamente.');
                } else {
                    $this->error('Error al eliminar webhook: ' . ($res['description'] ?? 'Error desconocido'));
                }
                break;

            case 'status':
            default:
                $this->info('Consultando estado del webhook en Telegram...');
                $info = $telegramService->getWebhookInfo();
                if (!empty($info['ok'])) {
                    $result = $info['result'] ?? [];
                    $this->table(
                        ['Propiedad', 'Valor'],
                        [
                            ['URL configurada', $result['url'] ?: '(Ninguna)'],
                            ['Tiene certificado personalizado', !empty($result['has_custom_certificate']) ? 'Sí' : 'No'],
                            ['Actualizaciones pendientes', $result['pending_update_count'] ?? 0],
                            ['Último error fecha', !empty($result['last_error_date']) ? date('Y-m-d H:i:s', $result['last_error_date']) : 'Ninguno'],
                            ['Último mensaje de error', $result['last_error_message'] ?? 'Ninguno'],
                        ]
                    );
                } else {
                    $this->error('No se pudo consultar Telegram: ' . ($info['description'] ?? 'Error'));
                }
                break;
        }

        return 0;
    }
}
