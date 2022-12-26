<?php

namespace Albet\Asmvc\Core\Containers;

use DI\Container as DIContainer;
use DI\ContainerBuilder;

class Container
{
    private static ?DIContainer $container = null;

    public function __construct()
    {
        $containerBuilder = new ContainerBuilder();
        $config = config('container');
        if (env("APP_ENV", "development") === "production") {

            if (count($config) <= 1 && $config['CheckPerformance']) {
                throw new OptimizationRequiredException();
            }
            $containerBuilder->enableCompilation(cache_path());
        }
        unset($config['CheckPerformance']); // emit CheckPerformance so it won't get compiled
        $containerBuilder->addDefinitions($config);
        $container = $containerBuilder->build();
        self::$container = $container;
    }

    public static function make()
    {
        return new Container;
    }

    private static function checkForInstance()
    {
        if (!self::$container) {
            throw new ContainerNotBootedException();
        }
    }

    public static function getContainer()
    {
        self::checkForInstance();
        return self::$container;
    }

    public static function inject($instance, ?array $parameters = [])
    {
        self::checkForInstance();
        return self::$container->call($instance, $parameters);
    }

    public static function fullfil($instance)
    {
        self::checkForInstance();
        return self::$container->get($instance);
    }
}
