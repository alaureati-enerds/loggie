<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Loggie\Utils\LoggieFactory;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PDO;

$mailer = new PHPMailer(true);

try {
    $mailer->isSMTP();
    $mailer->Host = 'smtp.office365.com';
    $mailer->SMTPAuth = true;
    $mailer->Username = 'a.laureati@enerds.it';
    $mailer->Password = 'Suh78183';
    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mailer->Port = 587;
} catch (Exception $e) {
    echo "❌ Errore di configurazione SMTP: " . $e->getMessage() . "\n";
    exit(1);
}

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
        // [
        //     'type' => 'database',
        //     'pdo' => new PDO('mysql:host=loggie-db;port=3306;dbname=loggie;charset=utf8mb4', 'root', 'masterkey', [
        //         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        //     ]),
        //     'level' => 'debug',
        //     'channel' => 'factory'
        // ],
        [
            'type' => 'telegram',
            'token' => '7643862517:AAFldCpOh11_33IdsqlkkHAbAZ6lOfhrcB8',
            'chat_id' => '-1002638531051',
            'level' => 'error',
            'formatter' => 'interpolated'
        ],
        [
            'type' => 'email',
            'mailer' => $mailer,
            'to' => 'laureatiandrea@gmail.com',
            'from' => 'a.laureati@enerds.it',
            'subject' => 'Errore critico dal Logger',
            'level' => 'debug',
            'formatter' => 'line'
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
