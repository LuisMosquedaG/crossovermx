<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Redirige al usuario al bot de Telegram con su token de vinculación.
     */
    public function link(Request $request)
    {
        $user = $request->user();

        if (!$this->telegramService->isConfigured()) {
            return redirect()->back()->with('error', 'El servicio de Telegram aún no ha sido configurado por el administrador.');
        }

        $url = $this->telegramService->generateLinkUrl($user);

        if (!$url) {
            return redirect()->back()->with('error', 'No se ha configurado el nombre de usuario del Bot de Telegram (TELEGRAM_BOT_USERNAME).');
        }

        return redirect()->away($url);
    }

    /**
     * Desvincula la cuenta de Telegram del usuario autenticado.
     */
    public function unlink(Request $request)
    {
        $user = $request->user();
        $this->telegramService->unlinkUser($user);

        return redirect()->back()->with('message', 'Tu cuenta de Telegram ha sido desvinculada exitosamente.');
    }

    /**
     * Envía un mensaje de prueba al usuario autenticado.
     */
    public function testNotification(Request $request)
    {
        $user = $request->user();

        if (empty($user->telegram_chat_id)) {
            return redirect()->back()->with('error', 'Primero debes vincular tu cuenta de Telegram para realizar una prueba.');
        }

        $testMessage = "🏀 *¡Prueba de Notificación Exitosa!*\n\n"
                     . "Hola *{$user->name}*, tu cuenta está correctamente sincronizada.\n"
                     . "A partir de ahora recibirás aquí los avisos de tus partidos programados (rival, fecha, horario y cancha). 🚀";

        $response = $this->telegramService->sendMessage($user->telegram_chat_id, $testMessage);

        if (!empty($response['ok'])) {
            return redirect()->back()->with('message', '¡Mensaje de prueba enviado! Revisa tu aplicación de Telegram.');
        }

        return redirect()->back()->with('error', 'No se pudo enviar el mensaje: ' . ($response['description'] ?? 'Error desconocido'));
    }

    /**
     * Webhook que procesa los mensajes y comandos enviados desde Telegram.
     */
    public function webhook(Request $request)
    {
        $update = $request->all();
        Log::info('Telegram Webhook recibido:', $update);

        $this->telegramService->handleUpdate($update);

        return response()->json(['ok' => true]);
    }
}
