<?php

use Rector\Config\RectorConfig;
use Rector\PHPUnit\PHPUnit60\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/packages/*/tests/src/',
        __DIR__.'/packages/*/src/',
    ])
    ->withPreparedSets(
        phpunit: true
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
        ]
    ])
    ->withRules([
    ])
;
