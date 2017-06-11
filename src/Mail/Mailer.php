<?php
declare(strict_types=1);

namespace noFlash\NotifyBot\Mail;

use noFlash\NotifyBot\Configuration\SmtpConfiguration;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $from;

    public function __construct(
        \Swift_Mailer $mailer,
        SmtpConfiguration $smtpConfiguration)
    {
        $this->mailer = $mailer;
        $this->setFrom($smtpConfiguration->getFrom());
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     *
     * @return Mailer
     */
    public function setFrom(string $from): Mailer
    {
        $this->from = $from;

        return $this;
    }

    public function sendTextMessage(string $to, string $subject, string $text): bool
    {
        $msg = new \Swift_Message($subject, $text, 'text/plain');
        $msg->setTo($to);
        $msg->setFrom($this->from);

        return (bool)$this->mailer->send($msg);
    }
}
