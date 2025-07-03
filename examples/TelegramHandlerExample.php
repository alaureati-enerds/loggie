

<?php

date_default_timezone_set('Europe/Rome');

require_once __DIR__ . '/../vendor/autoload.php';

use Loggie\Logger;
use Loggie\Handlers\TelegramHandler;
use Loggie\Formatters\TelegramFormatter;

// Inserisci il tuo token e chat ID qui
$botToken = '7643862517:AAFldCpOh11_33IdsqlkkHAbAZ6lOfhrcB8';
// $chatId = '7822744372';
$chatId = "-1002638531051"; // ID Canale

// Crea il formatter (opzionale)
$formatter = new TelegramFormatter();

// Crea l'handler Telegram
$telegramHandler = new TelegramHandler(
    $botToken,
    $chatId,
    'debug',
    $formatter
);

// Crea il logger e aggiungi l'handler
$logger = new Logger();
$logger->addHandler($telegramHandler);

// Esempi di log
$logger->debug("Questo è un messaggio di debug da {utente}", ['utente' => 'Andrea']);
$logger->warning("Attenzione! Qualcosa potrebbe non andare", ['file' => __FILE__]);
$logger->error("Errore critico riscontrato nel modulo X", ['code' => 1234]);
