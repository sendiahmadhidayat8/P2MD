<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Realtime; // Pastikan namespace ini sesuai dengan namespace yang digunakan dalam file Realtime.php

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Realtime()
        )
    ),
    8080
);

$server->run();
