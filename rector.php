<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $paths = array_map(fn ($item) => __DIR__ . "/App/" . $item, array_values(array_diff(scandir(__DIR__ . '/App'), ['.', '..', 'Core'])));

    // var_dump($paths);


    $rectorConfig->paths(array_merge($paths, [__DIR__ . "/public/"]));

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_80,
        SetList::DEAD_CODE,
        SetList::TYPE_DECLARATION,
        SetList::CODE_QUALITY,
        SetList::EARLY_RETURN
    ]);
};
