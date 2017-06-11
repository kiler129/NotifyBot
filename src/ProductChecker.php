<?php
declare(strict_types=1);

namespace noFlash\NotifyBot;

use GuzzleHttp\Client;
use noFlash\NotifyBot\Configuration\NotificationConfiguration;
use noFlash\NotifyBot\Configuration\ProductConfiguration;

class ProductChecker
{
    private const URL_PATTERN = 'https://www.apple.com/shop/retail/pickup-message?parts.0=%s&location=%s';

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $productCode;

    /**
     * @var array|null
     */
    private $lastResult;

    /**
     * @var int
     */
    private $freshUntil = -1;

    /**
     * @var int
     */
    private $freshInterval = -1;

    private function __construct(string $partNumber, string $location)
    {
        $this->productCode = $partNumber;

        $this->url = $this->buildUrl($this->productCode, $location);
    }

    private function buildUrl(string $partNumber, string $location)
    {
        return sprintf(self::URL_PATTERN, rawurlencode($partNumber), $location);
    }

    public static function createFromConfiguration(
        ProductConfiguration $productConfiguration,
        NotificationConfiguration $notificationConfiguration): self
    {
        $instance = new self(
            $productConfiguration->getCode(), $notificationConfiguration->getLocation()
        );
        $instance->setFreshInterval($notificationConfiguration->getInterval());

        return $instance;
    }

    public function setFreshInterval(int $seconds): self
    {
        $this->freshInterval = $seconds;

        return $this;
    }

    public function isAvailableToday(): bool
    {
        foreach ($this->getAvailability() as $avDetails) {
            if ($avDetails['waitDays'] <= 0) {
                return true;
            }
        }

        return false;
    }

    public function getAvailability(): \Generator
    {
        $today = new \DateTimeImmutable('today');

        foreach ($this->getStoresStock() as $storeInformation) {
            if (!isset($storeInformation['partsAvailability'][$this->productCode])) {
                continue; //Part not available in store
            }

            $avDetails = $storeInformation['partsAvailability'][$this->productCode];
            $atPos = strpos($avDetails['storePickupQuote'], ' at');
            $when = ($atPos === false) ? $avDetails['storePickupQuote'] : substr(
                $avDetails['storePickupQuote'],
                0,
                $atPos
            );

            $when = new \DateTimeImmutable($when);

            yield [
                'where'    => sprintf(
                    '%s in %s',
                    $storeInformation['storeName'],
                    $storeInformation['city']
                ),
                'when'     => $when,
                'waitDays' => (int)$today->diff($when)->format('%R%a')
            ];
        }
    }

    public function getStoresStock(): array
    {
        if (time() < $this->freshUntil) {
            return $this->lastResult;
        }

        $client = new Client(['timeout' => 2.0]);
        $response = $client->get($this->url);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->lastResult = $body['body']['stores'] ?? [];

        //@todo check for errors
        $this->freshUntil = time() + $this->freshInterval;

        return $this->lastResult;
    }

    public function whereAvailableToday(): ?string
    {
        foreach ($this->getAvailability() as $avDetails) {
            if (strtolower($avDetails['when']) === 'today') {
                return $avDetails['where'];
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getProductCode()
    {
        return $this->productCode;
    }

}
