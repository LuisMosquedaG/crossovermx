<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

echo "=== TOURNAMENTS ===\n";
$tournaments = \App\Models\Tournament::all();
foreach ($tournaments as $t) {
    echo sprintf("ID: %d | Name: %s | Client ID: %s\n", $t->id, $t->name, $t->client_id ?? 'NULL');
}

echo "\n=== USERS ===\n";
$users = \App\Models\User::all();
foreach ($users as $u) {
    echo sprintf("ID: %d | Name: %s | Client ID: %s | Role: %s\n", $u->id, $u->name, $u->client_id ?? 'NULL', $u->role->name ?? 'N/A');
}
