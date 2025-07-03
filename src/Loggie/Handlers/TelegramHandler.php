<?php

namespace Loggie\Handlers;

use Loggie\Handlers\HandlerInterface;
use Loggie\Formatters\FormatterInterface;
use Loggie\Utils\LoggieLevels;
use RuntimeException;

/**
 * Handler che invia i log come messaggi Telegram tramite Bot API.
 *
 * Questo handler utilizza un bot Telegram per inviare messaggi a una chat o canale.
 * Supporta l'utilizzo di un formatter compatibile con Markdown per il messaggio.
 *
 * Caratteristiche:
 * - Livello minimo di log configurabile
 * - Supporta il parse_mode Markdown (versione 1)
 * - Invia direttamente tramite cURL alla Telegram API
 */
class TelegramHandler implements HandlerInterface
{
    private string $botToken;
    private string $chatId;
    private string $minLevel;
    private ?FormatterInterface $formatter;

    /**
     * @param string $botToken  Token del bot Telegram.
     * @param string $chatId    ID della chat o del canale Telegram.
     * @param string $minLevel  Livello minimo di log da gestire.
     * @param FormatterInterface|null $formatter Formatter opzionale per il messaggio.
     */
    public function __construct(string $botToken, string $chatId, string $minLevel = 'debug', ?FormatterInterface $formatter = null)
    {
        $this->botToken = $botToken;
        $this->chatId = $chatId;
        $this->minLevel = $minLevel;
        $this->formatter = $formatter;
    }

    /**
     * Invia un messaggio alla chat Telegram se il livello è sufficiente.
     *
     * @param string $level   Livello del log (es. 'error', 'warning')
     * @param string $message Messaggio principale
     * @param array $context  Dati contestuali da includere nel log
     *
     * @throws RuntimeException Se la richiesta Telegram fallisce o restituisce errore
     */
    public function write(string $level, string $message, array $context = []): void
    {
        if (LoggieLevels::compare($level, $this->minLevel) < 0) {
            return;
        }

        if ($this->formatter) {
            $message = $this->formatter->format($level, $message, $context);
        }

        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        $postFields = [
            'chat_id' => $this->chatId,
            'text' => $message,
            'parse_mode' => 'Markdown',
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode !== 200) {
            throw new RuntimeException("Telegram API error ({$httpCode}): " . $response . ($error ? " | cURL error: $error" : ""));
        }
    }

    /**
     * Imposta un formatter da utilizzare per i messaggi log.
     *
     * @param FormatterInterface|null $formatter Formatter personalizzato
     */
    public function setFormatter(?FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    /**
     * Restituisce il formatter attualmente utilizzato.
     *
     * @return FormatterInterface|null
     */
    public function getFormatter(): ?FormatterInterface
    {
        return $this->formatter;
    }
}
