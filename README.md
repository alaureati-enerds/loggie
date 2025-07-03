# Loggie 🐾

Loggie è una libreria di logging moderna, flessibile e compatibile PSR-3, sviluppata in PHP con l'obiettivo di offrire una gestione dei log elegante e integrabile in ambienti reali. Supporta diversi tipi di handler e formatter per adattarsi a molteplici esigenze.

## ✨ Caratteristiche

- ✅ Compatibile PSR-3 (`LoggerInterface`)
- 📁 Log su file, database, console, Telegram, email, e null handler
- 🎨 Supporta formatter personalizzati (Interpolated, Line, Telegram)
- 💡 Estendibile con handler e formatter personalizzati
- 🐘 Richiede PHP >= 8.1

## 📦 Installazione

```bash
composer require alaureati-enerds/loggie
```

## 🧰 Esempio rapido

### Logging su Console

```php
use Loggie\Logger;
use Loggie\Handlers\ConsoleHandler;
use Loggie\Formatters\LineFormatter;
use Loggie\Utils\LoggieLevels;

$handler = new ConsoleHandler(STDOUT, LoggieLevels::DEBUG);
$handler->setFormatter(new LineFormatter());

$logger = new Logger([$handler]);
$logger->info("Applicazione avviata.");
```

### Logging su Telegram

```php
use Loggie\Logger;
use Loggie\Handlers\TelegramHandler;
use Loggie\Formatters\TelegramFormatter;

$logger = new Logger();
$telegram = new TelegramHandler('YOUR_BOT_TOKEN', 'YOUR_CHAT_ID', 'debug', new TelegramFormatter());
$logger->addHandler($telegram);

$logger->warning("Problema rilevato", ['file' => 'index.php']);
```

### Logging via Email con PHPMailer

```php
use Loggie\Handlers\EmailHandler;
use PHPMailer\PHPMailer\PHPMailer;

$mailer = new PHPMailer(true);
// ... configura SMTP ...

$emailHandler = new EmailHandler($mailer, 'admin@example.com', 'bot@example.com', 'Log di sistema');
$logger->addHandler($emailHandler);
```

## 🧱 Handler disponibili

- `ConsoleHandler` – Log a console (STDOUT/STDERR)
- `FileHandler` – Log su file
- `DatabaseHandler` – Log su MySQL
- `TelegramHandler` – Invio log via Telegram Bot
- `EmailHandler` – Invio log via email (PHPMailer)
- `NullHandler` – Ignora tutti i log

## 🎨 Formatter disponibili

- `LineFormatter` – Formattazione semplice [DATA] LIVELLO: messaggio
- `InterpolatedFormatter` – Supporta segnaposto come `{user}` con context
- `TelegramFormatter` – Formattazione compatibile Markdown V2 con emoji

## 🔧 Requisiti

- PHP >= 8.1
- Estensioni PHP: `curl`, `pdo`, `mbstring`
- Librerie:
  - `phpmailer/phpmailer`
  - `psr/log`

## 📂 Autoload

```json
"autoload": {
    "psr-4": {
        "Loggie\\": "src/Loggie/"
    }
}
```

## 👤 Autore

**Andrea Laureati** – [a.laureati@enerds.it](mailto:a.laureati@enerds.it)  
Sviluppato per eNerds Srl – [enerds.it](https://www.enerds.it)

## 📄 Licenza

Rilasciato sotto licenza MIT.
