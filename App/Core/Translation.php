<?php

namespace App\Asmvc\Core;

use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

class Translation
{
    private static ?Translator $translationService = null;

    public static function boot()
    {
        self::$translationService = new Translator("en");
        self::$translationService->addLoader('array', new ArrayLoader);
        self::$translationService->setFallbackLocales(["en", "id"]);

        (new self)->registerResource();
    }

    public function registerResource()
    {
        $langs = array_diff_key(scandir(__DIR__ . "/../Languages"), ['.', '..']);

        foreach ($langs as $lang) {
            $resources = require __DIR__ . "/../Languages/$lang";
            self::$translationService->addResource('array', $resources, explode('.', $lang)[0]);
        }
    }

    public static function setLocale(string $locale): void
    {
        self::$translationService->setLocale($locale);
    }

    public function trans(string $key, ?string $locale = 'en')
    {
        return self::$translationService->trans($key, [], null, $locale);
    }

    public function getTranslationServiceInstance()
    {
        return self::$translationService;
    }
}
