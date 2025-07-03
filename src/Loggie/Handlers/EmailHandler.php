<?php

namespace Loggie\Handlers;

use Loggie\Formatters\FormatterInterface;
use Loggie\Utils\LoggieLevels;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Gestisce l'invio di log tramite email utilizzando PHPMailer.
 *
 * Questo handler invia messaggi email a uno o più destinatari
 * in base al livello minimo configurato. Supporta anche formattazione
 * personalizzata tramite un oggetto FormatterInterface.
 *
 * Caratteristiche:
 * - Supporta destinatari, CC, BCC multipli
 * - Utilizza PHPMailer già configurato
 * - Supporta formattatori Markdown, plain text, ecc.
 */
class EmailHandler implements HandlerInterface
{
    private PHPMailer $mailer;
    private array $cc = [];
    private array $bcc = [];
    private array $to = [];
    private string $from;
    private string|null $fromName = null;
    private string $subject;
    private string $minLevel;
    private FormatterInterface|null $formatter = null;

    /**
     * Costruttore del gestore email.
     *
     * @param PHPMailer $mailer Oggetto PHPMailer già configurato
     * @param array|string $to Destinatario o elenco di destinatari
     * @param string $from Indirizzo mittente
     * @param string $subject Oggetto dell'email
     * @param string $minLevel Livello minimo per il logging
     * @param string|null $fromName Nome mittente opzionale
     * @param array $cc Elenco indirizzi CC
     * @param array $bcc Elenco indirizzi BCC
     */
    public function __construct(
        PHPMailer $mailer,
        array|string $to,
        string $from,
        string $subject,
        string $minLevel = LoggieLevels::ERROR,
        string|null $fromName = null,
        array $cc = [],
        array $bcc = []
    ) {
        $this->mailer = $mailer;
        $this->to = is_array($to) ? $to : [$to];
        $this->from = $from;
        $this->subject = $subject;
        $this->minLevel = $minLevel;
        $this->fromName = $fromName;
        $this->cc = $cc;
        $this->bcc = $bcc;
    }

    /**
     * Imposta un formatter per il corpo del messaggio.
     *
     * @param FormatterInterface $formatter Formatter da usare
     */
    public function setFormatter(FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    /**
     * Invia una email con il log se il livello è sufficiente.
     *
     * @param string $level Livello del messaggio (es. 'error', 'warning')
     * @param string $message Testo del log
     * @param array $context Contesto aggiuntivo per interpolazione o dettagli
     */
    public function write(string $level, string $message, array $context = []): void
    {
        if (LoggieLevels::compare($level, $this->minLevel) < 0) {
            return;
        }

        $body = $this->formatter
            ? $this->formatter->format($level, $message, $context)
            : "[{$level}] {$message}";

        try {
            $this->mailer->clearAllRecipients();
            $this->mailer->setFrom($this->from, $this->fromName ?? '');

            foreach ($this->to as $address) {
                $this->mailer->addAddress($address);
            }
            foreach ($this->cc as $ccAddress) {
                $this->mailer->addCC($ccAddress);
            }
            foreach ($this->bcc as $bccAddress) {
                $this->mailer->addBCC($bccAddress);
            }

            $this->mailer->Subject = $this->subject;
            $this->mailer->Body = $body;

            $this->mailer->send();
        } catch (Exception $e) {
            // Silenziare eccezioni per non bloccare il flusso principale
            // oppure loggarle su un handler secondario
        }
    }
}
