<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Loggie\Logger;
use Loggie\Handlers\ConsoleHandler;
use Loggie\Utils\LoggieLevels;
use Loggie\Formatters\InterpolatedFormatter;
use Loggie\Formatters\LineFormatter;

echo "ESEMPIO: ConsoleHandler con LineFormatter\n";
try {
    $lineHandler = new ConsoleHandler(STDOUT, LoggieLevels::DEBUG);
    $lineHandler->enableColors();
    $lineHandler->setFormatter(new LineFormatter());

    $logger = new Logger([$lineHandler]);

    $logger->debug("Debug attivato");
    $logger->info("Processo completato");
    $logger->warning("Attenzione! Livello di memoria basso");
    $logger->error("Errore critico in fase di avvio");

    echo "✅ LineFormatter testato con successo\n\n";
} catch (\Throwable $e) {
    echo "❌ Errore durante il test LineFormatter: " . $e->getMessage() . "\n";
}

echo "ESEMPIO: ConsoleHandler con InterpolatedFormatter\n";
try {
    $interpHandler = new ConsoleHandler(STDOUT, LoggieLevels::INFO);
    $interpHandler->enableColors();
    $interpHandler->setFormatter(new InterpolatedFormatter());

    $logger = new Logger([$interpHandler]);

    $logger->info("Utente {user} ha eseguito l'accesso da IP {ip}", [
        'user' => 'admin',
        'ip' => '192.168.1.10'
    ]);

    $logger->notice("Tentativo di connessione al database {db}", [
        'db' => 'main_db'
    ]);

    $logger->debug("Questo non si vedrà, livello troppo basso");

    echo "✅ InterpolatedFormatter testato con successo\n";
} catch (\Throwable $e) {
    echo "❌ Errore durante il test InterpolatedFormatter: " . $e->getMessage() . "\n";
}
