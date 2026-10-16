<?php

use Rector\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/packages/*/src/',
        __DIR__.'/packages/*/tests/src/',
        __DIR__.'/packages/*/utils/src/',
    ])
    ->withPreparedSets(
    )
    ->withComposerBased(phpunit: true)
    ->withPhpSets(
        php81: true
    )
    ->withSkip([
        // Psalm 6.x has bug: https://github.com/vimeo/psalm/issues/11038
        // We'll avoid first-class callables for the moment and prefer delegation.
        ArrowFunctionDelegatingCallToFirstClassCallableRector::class => true,
    ])
    ->withRules([
        RemoveUselessParamTagRector::class,
        RemoveUselessReadOnlyTagRector::class,
        RemoveUselessReturnTagRector::class,
        RemoveUselessVarTagRector::class,
    ])
    ->withImportNames(
        importShortClasses: false,
        removeUnusedImports: true
    )
    ->withConfiguredRule(RenameClassRector::class, [
    ])
;



