<?php

namespace Loggie\Handlers;

use Loggie\Formatters\FormatterInterface;
use Loggie\Utils\LoggieLevels;
use PDO;
use PDOException;

/**
 * Handler che salva i log in una tabella MySQL.
 *
 * Supporta la scrittura condizionale in base al livello minimo e permette
 * la personalizzazione del nome della tabella, del canale e del formatter.
 * In fase di inizializzazione, crea automaticamente la tabella se non esiste.
 */
class DatabaseHandler implements HandlerInterface
{
    private PDO $pdo;
    private string $table;
    private string $minLevel;
    private string $channel;
    private ?FormatterInterface $formatter = null;

    /**
     * @param PDO    $pdo      Connessione PDO al database MySQL.
     * @param string $table    Nome della tabella da utilizzare per il logging.
     * @param string $minLevel Livello minimo da loggare.
     * @param string $channel  Canale di log (per distinguere le sorgenti dei messaggi).
     */
    public function __construct(PDO $pdo, string $table = 'log', string $minLevel = 'debug', string $channel = 'default')
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->minLevel = $minLevel;
        $this->channel = $channel;
        $this->initializeTable();
    }

    /**
     * Imposta un formatter per formattare i messaggi prima di salvarli nel database.
     *
     * @param FormatterInterface $formatter Il formatter da utilizzare.
     */
    public function setFormatter(FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    /**
     * Scrive un messaggio di log nel database se il livello è sufficiente.
     *
     * @param string $level   Livello del messaggio di log.
     * @param string $message Messaggio da loggare.
     * @param array  $context Dati di contesto associati al log (verranno serializzati in JSON).
     */
    public function write(string $level, string $message, array $context = []): void
    {
        if (LoggieLevels::compare($level, $this->minLevel) < 0) {
            return;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO `{$this->table}` (`level`, `channel`, `message`, `context`, `timestamp`)
            VALUES (:level, :channel, :message, :context, NOW())
        ");

        $stmt->execute([
            'level' => $level,
            'channel' => $this->channel,
            'message' => $message,
            'context' => json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }

    private function initializeTable(): void
    {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS `{$this->table}` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `level` VARCHAR(20) NOT NULL,
                    `channel` VARCHAR(50) NOT NULL DEFAULT 'default',
                    `message` TEXT NOT NULL,
                    `context` JSON DEFAULT NULL,
                    `timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            // Log silent failure or rethrow depending on your needs
            error_log("Failed to initialize log table:" . $e->getMessage());
        }
    }
}
