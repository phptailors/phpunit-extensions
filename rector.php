<?php

use Rector\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\PHPUnit\PHPUnit60\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/packages/*/tests/static-analysis/',
        __DIR__.'/packages/*/tests/src/',
        __DIR__.'/packages/*/src/',
    ])
    ->withPreparedSets(
    )
    ->withSets([
        PHPUnitSetList::PHPUNIT_60,
        PHPUnitSetList::PHPUNIT_70,
        PHPUnitSetList::PHPUNIT_80,
        PHPUnitSetList::PHPUNIT_90,
        PHPUnitSetList::PHPUNIT_100,
    ])
    ->withPhpSets(
        php82: true
    )
    ->withSkip([
        AddDoesNotPerformAssertionToNonAssertingTestRector::class => [
            __DIR__.'/packages/phpunit-arrays/tests/src/Constraint/ArrayValuesEqualToTest.php',
            __DIR__.'/packages/phpunit-arrays/tests/src/Constraint/ArrayValuesIdenticalToTest.php',
            __DIR__.'/packages/phpunit-arrays/tests/src/Constraint/KsortedArrayEqualToTest.php',
            __DIR__.'/packages/phpunit-arrays/tests/src/Constraint/KsortedArrayIdenticalToTest.php',
            __DIR__.'/packages/phpunit-methods/tests/src/Constraint/HasMethodTest.php',
            __DIR__.'/packages/phpunit-properties/tests/src/Constraint/ClassPropertiesEqualToTest.php',
            __DIR__.'/packages/phpunit-properties/tests/src/Constraint/ClassPropertiesIdenticalToTest.php',
            __DIR__.'/packages/phpunit-properties/tests/src/Constraint/ObjectPropertiesEqualToTest.php',
            __DIR__.'/packages/phpunit-properties/tests/src/Constraint/ObjectPropertiesIdenticalToTest.php',
        ],
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


