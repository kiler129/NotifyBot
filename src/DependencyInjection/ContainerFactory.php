<?php
declare(strict_types=1);

namespace noFlash\NotifyBot\DependencyInjection;

use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContainerFactory
{
    private const COMPILED_CONTAINER = __DIR__ . '/../../cache/container.php';

    public static function getContainer(bool $debug = false): ContainerInterface
    {
        if (!$debug && file_exists(self::COMPILED_CONTAINER)) {
            require_once self::COMPILED_CONTAINER;

            return new \ProjectServiceContainer();
        }

        return self::createContainer();
    }

    private static function createContainer(): ContainerInterface
    {
        $container = new ContainerBuilder();
        $loader = new YamlFileLoader(
            $container, new FileLocator(__DIR__ . '/../Configuration')
        );
        $loader->load('services.yml');

        $container->compile();

        $dumper = new PhpDumper($container);
        file_put_contents(self::COMPILED_CONTAINER, $dumper->dump());

        return $container;
    }
}
