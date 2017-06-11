<?php
declare(strict_types=1);

namespace noFlash\NotifyBot\Configuration;

class SmtpConfiguration
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var bool
     */
    private $tls;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $pass;

    /**
     * @var string
     */
    private $from;

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return SmtpConfiguration
     */
    public function setHost(string $host): SmtpConfiguration
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     *
     * @return SmtpConfiguration
     */
    public function setPort(int $port): SmtpConfiguration
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTls()
    {
        return $this->tls;
    }

    /**
     * @param bool $tls
     *
     * @return SmtpConfiguration
     */
    public function setTls(bool $tls): SmtpConfiguration
    {
        $this->tls = $tls;

        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     *
     * @return SmtpConfiguration
     */
    public function setUser(string $user): SmtpConfiguration
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     *
     * @return SmtpConfiguration
     */
    public function setPass(string $pass): SmtpConfiguration
    {
        $this->pass = $pass;

        return $this;
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
     * @return SmtpConfiguration
     */
    public function setFrom(string $from): SmtpConfiguration
    {
        $this->from = $from;

        return $this;
    }
}
