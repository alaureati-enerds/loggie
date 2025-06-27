<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Loggie\Logger;
use Loggie\Handlers\FileHandler;

$logger = new Logger(new FileHandler(__DIR__ . '/FileHandlerExample.txt'));
$logger->info('Utente loggato: {user}', ['user' => 'Andrea']);
