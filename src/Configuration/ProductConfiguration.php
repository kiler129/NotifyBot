<?php
declare(strict_types=1);

namespace noFlash\NotifyBot\Configuration;

class ProductConfiguration
{
    /**
     * @var string
     */
    private $code;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return ProductConfiguration
     */
    public function setCode(string $code): ProductConfiguration
    {
        $this->code = $code;

        return $this;
    }
}
