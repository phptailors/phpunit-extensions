<?php

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
        PHPUnitSetList::PHPUNIT_90
    ])
    ->withPhpSets(
        php73: true
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
        // packages/phpunit-common/src
        'Tailors\PHPUnit\Values\AbstractConstraint' => 'Tailors\PHPUnit\Recursive\AbstractRecursiveConstraint',
        'Tailors\PHPUnit\Values\ConstraintImplementationTrait' => 'Tailors\PHPUnit\Recursive\RecursiveConstraintSpecializationTrait',
        'Tailors\PHPUnit\Values\ConstraintTestCase' => 'Tailors\PHPUnit\Recursive\RecursiveConstraintTestCase',

        'Tailors\PHPUnit\Recursive\AbstractConstraint' => 'Tailors\PHPUnit\Recursive\AbstractRecursiveConstraint',
        'Tailors\PHPUnit\Recursive\ConstraintImplementationTrait' => 'Tailors\PHPUnit\Recursive\RecursiveConstraintSpecializationTrait',
        'Tailors\PHPUnit\Recursive\ConstraintTestCase' => 'Tailors\PHPUnit\Recursive\RecursiveConstraintTestCase',

        'Tailors\PHPUnit\Recursive\AbstractGenericValues' => 'Tailors\PHPUnit\Values\AbstractGenericValues',
        'Tailors\PHPUnit\Recursive\AbstractValues' => 'Tailors\PHPUnit\Values\AbstractValues',
        'Tailors\PHPUnit\Recursive\AbstractValuesTestCase' => 'Tailors\PHPUnit\Values\AbstractValuesTestCase',
        'Tailors\PHPUnit\Recursive\ActualValues' => 'Tailors\PHPUnit\Values\ActualValues',
        'Tailors\PHPUnit\Recursive\ExpectedValues' => 'Tailors\PHPUnit\Values\ExpectedValues',
        'Tailors\PHPUnit\Recursive\ValuesInterface' => 'Tailors\PHPUnit\Values\ValuesInterface',
        'Tailors\PHPUnit\Recursive\ValuesWrapperInterface' => 'Tailors\PHPUnit\Values\ValuesWrapperInterface',

        // packages/phpunit-common/tests/src
        'Tailors\PHPUnit\Values\AbstractConstraintTest' => 'Tailors\PHPUnit\Recursive\AbstractRecursiveConstraintTest',
        'Tailors\PHPUnit\Values\ConstraintImplementationTraitTest' => 'Tailors\PHPUnit\Recursive\RecursiveConstraintSpecializationTraitTest',
        'Tailors\PHPUnit\Values\ConstraintTestCaseTest' => 'Tailors\PHPUnit\Recursive\RecursiveConstraintTestCaseTest',
        'Tailors\PHPUnit\Values\DummyAbstractConstraint' => 'Tailors\PHPUnit\Recursive\DummyAbstractRecursiveConstraint',
        'Tailors\PHPUnit\Values\DummyAbstractConstraintTest' => 'Tailors\PHPUnit\Recursive\DummyAbstractRecursiveConstraintTest',
        'Tailors\PHPUnit\Values\DummyConstraintImplementation' => 'Tailors\PHPUnit\Recursive\DummyRecursiveConstraintSpecialization',

        'Tailors\PHPUnit\Recursive\AbstractConstraintTest' => 'Tailors\PHPUnit\Recursive\AbstractRecursiveConstraintTest',
        'Tailors\PHPUnit\Recursive\ConstraintImplementationTraitTest' => 'Tailors\PHPUnit\Recursive\RecursiveConstraintSpecializationTraitTest',
        'Tailors\PHPUnit\Recursive\ConstraintTestCaseTest' => 'Tailors\PHPUnit\Recursive\RecursiveConstraintTestCaseTest',
        'Tailors\PHPUnit\Recursive\DummyAbstractConstraint' => 'Tailors\PHPUnit\Recursive\DummyAbstractRecursiveConstraint',
        'Tailors\PHPUnit\Recursive\DummyAbstractConstraintTest' => 'Tailors\PHPUnit\Recursive\DummyAbstractRecursiveConstraintTest',
        'Tailors\PHPUnit\Recursive\DummyConstraintImplementation' => 'Tailors\PHPUnit\Recursive\DummyRecursiveConstraintSpecialization',

        'Tailors\PHPUnit\Recursive\ActualValuesTest' => 'Tailors\PHPUnit\Values\ActualValuesTest',
        'Tailors\PHPUnit\Recursive\DummyExpectedValues' => 'Tailors\PHPUnit\Values\DummyExpectedValues',
        'Tailors\PHPUnit\Recursive\DummyExpectedValuesTest' => 'Tailors\PHPUnit\Values\DummyExpectedValuesTest',
        'Tailors\PHPUnit\Recursive\DummyValues' => 'Tailors\PHPUnit\Values\DummyValues',
        'Tailors\PHPUnit\Recursive\DummyValuesTest' => 'Tailors\PHPUnit\Values\DummyValuesTest',
        'Tailors\PHPUnit\Recursive\DummyValuesWrapper' => 'Tailors\PHPUnit\Values\DummyValuesWrapper',
        'Tailors\PHPUnit\Recursive\DummyValuesWrapperTest' => 'Tailors\PHPUnit\Values\DummyValuesWrapperTest',
        'Tailors\PHPUnit\Recursive\ExampleConstraint' => 'Tailors\PHPUnit\Values\ExampleConstraint',
        'Tailors\PHPUnit\Recursive\ExpectedValuesTest' => 'Tailors\PHPUnit\Values\ExpectedValuesTest',
        'Tailors\PHPUnit\Recursive\GenericValuesTestCase' => 'Tailors\PHPUnit\Values\GenericValuesTestCase',
    ])
;



