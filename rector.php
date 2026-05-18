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
        PHPUnitSetList::PHPUNIT_110,
    ])
    ->withPhpSets(
        php83: true
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
        'Tailors\PHPUnit\Values\RecursiveTraversal' => 'Tailors\PHPUnit\Recursive\RecursiveTraversal',
        'Tailors\PHPUnit\Values\RecursiveSelectorStackItem' => 'Tailors\PHPUnit\Recursive\RecursiveSelectorStackItem',
        'Tailors\PHPUnit\Values\RecursiveTraversalInterface' => 'Tailors\PHPUnit\Recursive\RecursiveTraversalInterface',
        'Tailors\PHPUnit\Values\RecursiveUnwrapper' => 'Tailors\PHPUnit\Recursive\RecursiveUnwrapper',
        'Tailors\PHPUnit\Values\RecursiveUnwrapperStackItem' => 'Tailors\PHPUnit\Recursive\RecursiveUnwrapperStackItem',
        'Tailors\PHPUnit\Values\RecursiveSelectorVisitor' => 'Tailors\PHPUnit\Recursive\RecursiveSelectorVisitor',
        'Tailors\PHPUnit\Values\RecursiveVisitorInterface' => 'Tailors\PHPUnit\Recursive\RecursiveVisitorInterface',
        'Tailors\PHPUnit\Values\RecursiveSelectorState' => 'Tailors\PHPUnit\Recursive\RecursiveSelectorState',
        'Tailors\PHPUnit\Values\RecursiveUnwrapperVisitor' => 'Tailors\PHPUnit\Recursive\RecursiveUnwrapperVisitor',
        'Tailors\PHPUnit\Values\RecursiveVisitorStackItemInterface' => 'Tailors\PHPUnit\Recursive\RecursiveVisitorStackItemInterface',
        'Tailors\PHPUnit\Values\RecursiveUnwrapperInterface' => 'Tailors\PHPUnit\Recursive\RecursiveUnwrapperInterface',

        'Tailors\PHPUnit\Recursive\AbstractConstraint' => 'Tailors\PHPUnit\Values\AbstractConstraint',
        'Tailors\PHPUnit\Recursive\AbstractGenericValues' => 'Tailors\PHPUnit\Values\AbstractGenericValues',
        'Tailors\PHPUnit\Recursive\AbstractValues' => 'Tailors\PHPUnit\Values\AbstractValues',
        'Tailors\PHPUnit\Recursive\AbstractValuesTestCase' => 'Tailors\PHPUnit\Values\AbstractValuesTestCase',
        'Tailors\PHPUnit\Recursive\ActualValues' => 'Tailors\PHPUnit\Values\ActualValues',
        'Tailors\PHPUnit\Recursive\ConstraintImplementationTrait' => 'Tailors\PHPUnit\Values\ConstraintImplementationTrait',
        'Tailors\PHPUnit\Recursive\ConstraintTestCase' => 'Tailors\PHPUnit\Values\ConstraintTestCase',
        'Tailors\PHPUnit\Recursive\ExpectedValues' => 'Tailors\PHPUnit\Values\ExpectedValues',
        'Tailors\PHPUnit\Recursive\ValuesInterface' => 'Tailors\PHPUnit\Values\ValuesInterface',
        'Tailors\PHPUnit\Recursive\ValuesWrapperInterface' => 'Tailors\PHPUnit\Values\ValuesWrapperInterface',

        // phpunit-common/tests/src
        'Tailors\PHPUnit\Values\RecursiveTraversalTest' => 'Tailors\PHPUnit\Recursive\RecursiveTraversalTest',
        'Tailors\PHPUnit\Values\DummyRecursiveVisitorStackItem' => 'Tailors\PHPUnit\Recursive\DummyRecursiveVisitorStackItem',
        'Tailors\PHPUnit\Values\RecursiveUnwrapperStackItemTest' => 'Tailors\PHPUnit\Recursive\RecursiveUnwrapperStackItemTest',
        'Tailors\PHPUnit\Values\DummyRecursiveVisitorTest' => 'Tailors\PHPUnit\Recursive\DummyRecursiveVisitorTest',
        'Tailors\PHPUnit\Values\RecursiveUnwrapperVisitorTest' => 'Tailors\PHPUnit\Recursive\RecursiveUnwrapperVisitorTest',
        'Tailors\PHPUnit\Values\RecursiveSelectorVisitorTest' => 'Tailors\PHPUnit\Recursive\RecursiveSelectorVisitorTest',
        'Tailors\PHPUnit\Values\RecursiveSelectorStackItemTest' => 'Tailors\PHPUnit\Recursive\RecursiveSelectorStackItemTest',
        'Tailors\PHPUnit\Values\RecursiveUnwrapperTest' => 'Tailors\PHPUnit\Recursive\RecursiveUnwrapperTest',
        'Tailors\PHPUnit\Values\DummyRecursiveVisitorStackItemTest' => 'Tailors\PHPUnit\Recursive\DummyRecursiveVisitorStackItemTest',
        'Tailors\PHPUnit\Values\DummyRecursiveVisitor' => 'Tailors\PHPUnit\Recursive\DummyRecursiveVisitor',
        'Tailors\PHPUnit\Values\RecursiveSelectorStateTest' => 'Tailors\PHPUnit\Recursive\RecursiveSelectorStateTest',

        'Tailors\PHPUnit\Recursive\AbstractConstraintTest' => 'Tailors\PHPUnit\Values\AbstractConstraintTest',
        'Tailors\PHPUnit\Recursive\ActualValuesTest' => 'Tailors\PHPUnit\Values\ActualValuesTest',
        'Tailors\PHPUnit\Recursive\ConstraintImplementationTraitTest' => 'Tailors\PHPUnit\Values\ConstraintImplementationTraitTest',
        'Tailors\PHPUnit\Recursive\ConstraintTestCaseTest' => 'Tailors\PHPUnit\Values\ConstraintTestCaseTest',
        'Tailors\PHPUnit\Recursive\DummyAbstractConstraint' => 'Tailors\PHPUnit\Values\DummyAbstractConstraint',
        'Tailors\PHPUnit\Recursive\DummyAbstractConstraintTest' => 'Tailors\PHPUnit\Values\DummyAbstractConstraintTest',
        'Tailors\PHPUnit\Recursive\DummyConstraintImplementation' => 'Tailors\PHPUnit\Values\DummyConstraintImplementation',
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


