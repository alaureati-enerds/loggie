<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Loggie\Logger;
use Loggie\Handlers\DatabaseHandler;

try {
    $pdo = new PDO('mysql:host=loggie-db;port=3306;dbname=loggie;charset=utf8mb4', 'root', 'masterkey', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $handlerOrdini = new DatabaseHandler($pdo, 'log', 'debug', 'ordini');
    $handlerPreventivi = new DatabaseHandler($pdo, 'log', 'debug', 'preventivi');

    $logger = new Logger([$handlerOrdini, $handlerPreventivi]);

    $logger->debug("Ordini: inizializzazione completata", ['module' => 'ordini-init']);
    echo "✅ Debug 'ordini' logged\n";

    $logger->info("Preventivi: calcolo completato", ['module' => 'preventivi-core']);
    echo "✅ Info 'preventivi' logged\n";

    $logger->warning("Ordini: pagamento non riuscito", ['order_id' => 456]);
    echo "✅ Warning 'ordini' logged\n";

    $logger->error("Preventivi: errore su configurazione", ['config' => 'missing']);
    echo "✅ Error 'preventivi' logged\n";

    $logger->critical("Ordini: servizio bloccato", ['service' => 'checkout']);
    echo "✅ Critical 'ordini' logged\n";

    echo "🎉 Tutti i log sono stati inseriti nel database.\n";
} catch (Throwable $e) {
    echo "❌ Errore nella connessione o nel logging: " . $e->getMessage() . "\n";
}
