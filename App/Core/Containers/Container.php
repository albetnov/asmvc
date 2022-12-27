<?php

namespace App\Asmvc\Core\Containers;

use DI\Container as DIContainer;
use DI\ContainerBuilder;

class Container
{
    private static ?DIContainer $container = null;

    /**
     * Prepare DI Container
     */
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

    /**
     * Make the container instance
     */
    public static function make(): self
    {
        return new Container;
    }

    /**
     * Check whenever the container has booted or not
     */
    private static function checkForInstance()
    {
        if (!self::$container) {
            throw new ContainerNotBootedException();
        }
    }

    /**
     * Get the contianer instance
     */
    public static function getContainer(): ?DIContainer
    {
        self::checkForInstance();
        return self::$container;
    }

    /** 
     * Inject a class using container.
     */
    public static function inject($instance, ?array $parameters = []): mixed
    {
        self::checkForInstance();
        return self::$container->call($instance, $parameters);
    }

    /**
     * Fulfill depedency requirements of a class
     */
    public static function fulfill($instance)
    {
        self::checkForInstance();
        return self::$container->get($instance);
    }
}
