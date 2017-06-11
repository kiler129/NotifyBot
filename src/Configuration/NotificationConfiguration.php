<?php
declare(strict_types=1);

namespace noFlash\NotifyBot\Configuration;

class NotificationConfiguration
{
    /**
     * @var string
     */
    private $to;

    /**
     * @var int
     */
    private $interval;

    /**
     * @var int
     */
    private $grace;

    /**
     * @var string
     */
    private $location;

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     *
     * @return NotificationConfiguration
     */
    public function setTo(string $to): NotificationConfiguration
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     *
     * @return NotificationConfiguration
     */
    public function setInterval(int $interval): NotificationConfiguration
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * @return int
     */
    public function getGrace()
    {
        return $this->grace;
    }

    /**
     * @param int $grace
     *
     * @return NotificationConfiguration
     */
    public function setGrace(int $grace): NotificationConfiguration
    {
        $this->grace = $grace;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     *
     * @return NotificationConfiguration
     */
    public function setLocation(string $location): NotificationConfiguration
    {
        $this->location = $location;

        return $this;
    }
}
