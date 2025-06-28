

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Loggie\Utils\LoggieFactory;

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
