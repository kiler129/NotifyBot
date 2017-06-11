<?php
declare(strict_types=1);

namespace noFlash\NotifyBot\Mail;

use noFlash\NotifyBot\Configuration\SmtpConfiguration;

class SmtpTransportFactory
{
    /**
     * @var SmtpConfiguration
     */
    private $smtpConfiguration;

    public function __construct(SmtpConfiguration $smtpConfiguration)
    {
        $this->smtpConfiguration = $smtpConfiguration;
    }

    public function createTransport(): \Swift_SmtpTransport
    {
        $transport = new \Swift_SmtpTransport(
            $this->smtpConfiguration->getHost(),
            $this->smtpConfiguration->getPort()
        );
        $transport->setUsername($this->smtpConfiguration->getUser())->setPassword(
                $this->smtpConfiguration->getPass()
            );

        if ($this->smtpConfiguration->isTls()) {
            $transport->setEncryption('tls');
        }

        return $transport;
    }
}
