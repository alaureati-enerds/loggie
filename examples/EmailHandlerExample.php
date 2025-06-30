

<?php

require __DIR__ . '/../vendor/autoload.php';

use Loggie\Logger;
use Loggie\Handlers\EmailHandler;
use Loggie\Formatters\InterpolatedFormatter;
use Loggie\Utils\LoggieLevels;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mailer = new PHPMailer(true);

try {
    $mailer->isSMTP();
    $mailer->Host = 'smtp.office365.com';
    $mailer->SMTPAuth = true;
    $mailer->Username = 'a.laureati@enerds.it';
    $mailer->Password = 'Suh78183';
    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mailer->Port = 587;

    // Crea l'handler email con opzioni estese
    $emailHandler = new EmailHandler(
        $mailer,
        ['laureatiandrea@gmail.com'],                   // Destinatari multipli
        'a.laureati@enerds.it',                         // Mittente
        'Loggie - Errore Critico',                      // Oggetto
        LoggieLevels::ERROR,
        'Andrea Laureati (Loggie Bot)',                 // Nome visualizzato mittente
        ['laureatiandrea@gmail.com']
    );

    // Formatter per il corpo del messaggio
    $formatter = new InterpolatedFormatter();
    $emailHandler->setFormatter($formatter);

    // Crea il logger e assegna l'handler
    $logger = new Logger();
    $logger->addHandler($emailHandler);

    // Scrive un messaggio di test (verrà inviato perché è livello ERROR)
    $logger->error('Errore nella sincronizzazione dell\'utente {user}', [
        'user' => 'm.rossi'
    ]);

    echo "Messaggio di test inviato.\n";
} catch (Exception $e) {
    echo "Errore durante l'invio dell'email: {$e->getMessage()}\n";
}
