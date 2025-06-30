<?php

namespace Loggie\Handlers;

use Loggie\Formatters\FormatterInterface;
use Loggie\Utils\LoggieLevels;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

    public function setFormatter(FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

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
