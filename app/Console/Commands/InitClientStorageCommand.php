<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Services\TenantStorageService;
use Illuminate\Console\Command;

class InitClientStorageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:init-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicializa las carpetas de almacenamiento para todos los clientes existentes en storage/app/public/clients/{id}/';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            $this->info('No hay clientes registrados en la base de datos.');
            return Command::SUCCESS;
        }

        $this->info("Inicializando estructura de carpetas para {$clients->count()} clientes...");

        foreach ($clients as $client) {
            TenantStorageService::initializeTenantDirectories($client->id);
            $this->line(" - Cliente #{$client->id} [{$client->name}] (prefix: {$client->prefix}): OK");
        }

        $this->info('¡Estructura de carpetas multi-tenant inicializada con éxito!');

        return Command::SUCCESS;
    }
}
