<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Loggie\Utils\LoggieFactory;
use PDO;

$config = [
    'handlers' => [
        [
            'type' => 'console',
            'level' => 'debug',
            'formatter' => 'interpolated',
            'colors' => true
        ],
        [
            'type' => 'file',
            'path' => __DIR__ . '/../logs/factory.log',
            'level' => 'warning',
            'formatter' => 'line'
        ],
        [
            'type' => 'null'
        ],
        [
            'type' => 'database',
            'pdo' => new PDO('mysql:host=loggie-db;port=3306;dbname=loggie;charset=utf8mb4', 'root', 'masterkey', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]),
            'level' => 'debug',
            'channel' => 'factory'
        ]
    ]
];

try {
    $logger = LoggieFactory::fromArray($config);

    $logger->debug("Avvio processo {id}", ['id' => 123]);
    $logger->info("Processo in corso...");
    $logger->warning("Possibile sovraccarico riscontrato");
    $logger->error("Errore critico! Codice: {code}", ['code' => 500]);

    echo "✅ Logger creato con successo tramite LoggieFactory.\n";
} catch (\Throwable $e) {
    echo "❌ Errore nella creazione del logger: " . $e->getMessage() . "\n";
}
