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
         // phpunit-common/src
        'Tailors\PHPUnit\Values\AbstractPropertySelector' => 'Tailors\PHPUnit\Selector\AbstractPropertySelector',
        'Tailors\PHPUnit\Values\AbstractValueSelector' => 'Tailors\PHPUnit\Selector\AbstractValueSelector',
        'Tailors\PHPUnit\Values\ArrayValueSelector' => 'Tailors\PHPUnit\Selector\ArrayValueSelector',
        'Tailors\PHPUnit\Values\ClassPropertySelector' => 'Tailors\PHPUnit\Selector\ClassPropertySelector',
        'Tailors\PHPUnit\Values\ObjectPropertySelector' => 'Tailors\PHPUnit\Selector\ObjectPropertySelector',
        'Tailors\PHPUnit\Values\ValueSelectorInterface' => 'Tailors\PHPUnit\Selector\ValueSelectorInterface',
        'Tailors\PHPUnit\Values\ValueSelectorWrapperInterface' => 'Tailors\PHPUnit\Selector\ValueSelectorWrapperInterface',

         // phpunit-common/tests/src
        'Tailors\PHPUnit\Values\ArrayValueSelectorTest' => 'Tailors\PHPUnit\Selector\ArrayValueSelectorTest',
        'Tailors\PHPUnit\Values\ClassPropertySelectorTest' => 'Tailors\PHPUnit\Selector\ClassPropertySelectorTest',
        'Tailors\PHPUnit\Values\DummyValueSelector' => 'Tailors\PHPUnit\Selector\DummyValueSelector',
        'Tailors\PHPUnit\Values\DummyValueSelectorTest' => 'Tailors\PHPUnit\Selector\DummyValueSelectorTest',
        'Tailors\PHPUnit\Values\ObjectPropertySelectorTest' => 'Tailors\PHPUnit\Selector\ObjectPropertySelectorTest',
    ])
;
