<?php
declare(strict_types=1);

namespace noFlash\NotifyBot\Configuration;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ConfigurationProvider
{
    private const CONFIG_LOCATION = __DIR__ . '/../../config.yml';

    /**
     * @var SmtpConfiguration
     */
    private $smtp;

    /**
     * @var ProductConfiguration
     */
    private $product;

    /**
     * @var NotificationConfiguration
     */
    private $notification;

    public static function getConfiguration()
    {

        $serializer = new Serializer(
            [new ObjectNormalizer(null, null, null, new ReflectionExtractor())],
            [new YamlEncoder()]
        );

        $config = file_get_contents(self::CONFIG_LOCATION);

        $data = $serializer->deserialize($config, self::class, 'yaml');

        return $data;
    }

    /**
     * @return SmtpConfiguration
     */
    public function getSmtp()
    {
        return $this->smtp;
    }

    /**
     * @param SmtpConfiguration $smtp
     *
     * @return ConfigurationProvider
     */
    public function setSmtp(SmtpConfiguration $smtp): ConfigurationProvider
    {
        $this->smtp = $smtp;

        return $this;
    }

    /**
     * @return ProductConfiguration
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ProductConfiguration $product
     *
     * @return ConfigurationProvider
     */
    public function setProduct(ProductConfiguration $product): ConfigurationProvider
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return NotificationConfiguration
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param NotificationConfiguration $notification
     *
     * @return ConfigurationProvider
     */
    public function setNotification(
        NotificationConfiguration $notification): ConfigurationProvider
    {
        $this->notification = $notification;

        return $this;
    }
}
