<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Operator;
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ArrayResult\ArrayResultInterface;
use Tailors\PHPUnit\ArrayResult\ExpectedArrayResult;
use Tailors\PHPUnit\ArraySpec\DummyArraySelectionOnly;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryInterface;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperInterface;
use Tailors\PHPUnit\ValueSelector\ArrayValueSelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveConstraint\AbstractRecursiveConstraint
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class AbstractRecursiveConstraintTest extends TestCase
{
    /**
     * @psalm-param ArrayLike $expected
     */
    public function createDummyConstraint(
        ?iterable $expected = null,
        ?ComparatorInterface $comparator = null,
        ?RecursiveResultFactoryInterface $recursiveResultFactory = null,
        ?RecursiveResultUnwrapperInterface $recursiveResultUnwrapper = null
    ): DummyAbstractRecursiveConstraint {
        if (null === $expected) {
            $expected = $this->createMock(\Traversable::class);
        }

        if (null === $comparator) {
            $comparator = $this->createMock(ComparatorInterface::class);
        }

        if (null === $recursiveResultFactory) {
            $recursiveResultFactory = $this->createMock(RecursiveResultFactoryInterface::class);
        }

        if (null === $recursiveResultUnwrapper) {
            $recursiveResultUnwrapper = $this->createMock(RecursiveResultUnwrapperInterface::class);
        }

        return DummyAbstractRecursiveConstraint::create($expected, $comparator, $recursiveResultFactory, $recursiveResultUnwrapper);
    }

//    public function createArrayValuesIdentityConstraint(array $expected)
//    {
//        return $this->createDummyConstraint(
//            new ExpectedArrayResult($expected),
//            new IdentityComparator(),
//            new ArrayValueSelector(),
//            new RecursiveUnwrapper()
//        );
//    }

    //
    //
    // TESTS
    //
    //

    public function testExtendsConstraint(): void
    {
        $constraint = $this->createDummyConstraint();
        $this->assertInstanceOf(Constraint::class, $constraint);
    }

    public function testToString(): void
    {
        $comparator = $this->createMock(ComparatorInterface::class);

        $valueSelector = $this->createMock(ValueSelectorInterface::class);

        $valueSelector->expects($this->once())
            ->method('subject')
            ->willReturn('a tree')
        ;

        $valueSelector->expects($this->once())
            ->method('selectable')
            ->willReturn('apples')
        ;

        $expected = new DummyArraySelectionOnly($valueSelector, $this->createMock(\Traversable::class));

        $comparator->expects($this->once())
            ->method('adjective')
            ->willReturn('having colors')
        ;


        $constraint = $this->createDummyConstraint($expected, $comparator);

        $this->assertSame('is a tree with apples having colors specified', $constraint->toString());
    }

    /**
     * @return iterable<string,array{operator: \Closure(TestCase):SelfDescribing, expect: mixed}>
     */
    public static function provToStringInContext(): iterable
    {
        $constraint = function (TestCase $test): Constraint {
            $expected = $test->createMock(\Traversable::class);
            $comparator = $test->createMock(ComparatorInterface::class);

            $valueSelector = $test->createMock(ValueSelectorInterface::class);

            $valueSelector->expects($test->any())
                ->method('subject')
                ->willReturn('a tree')
            ;

            $valueSelector->expects($test->any())
                ->method('selectable')
                ->willReturn('apples')
            ;

            $comparator->expects($test->any())
                ->method('adjective')
                ->willReturn('having colors')
            ;

            return static::createDummyConstraint($test, $expected, $comparator, $valueSelector);
        };

        yield 'AbstractConstraintTest.php:'.__LINE__ => [
            'operator' => function (TestCase $test) use ($constraint): Operator {
                return self::logicalNot($constraint($test));
            },
            'expect' => 'fails to be a tree with apples having colors specified',
        ];

        yield 'AbstractConstraintTest.php:'.__LINE__ => [
            'operator' => function (TestCase $test) use ($constraint): Operator {
                return self::logicalOr($constraint($test));
            },
            'expect' => 'is a tree with apples having colors specified',
        ];
    }
//
//    /**
//     * @psalm-param \Closure(TestCase $test):Operator $operator
//     *
//     * @dataProvider provToStringInContext
//     *
//     * @param mixed $expect
//     *
//     * @psalm-param \Closure(TestCase):SelfDescribing $operator
//     */
//    public function testToStringInContext(\Closure $operator, $expect): void
//    {
//        $this->assertSame($expect, $operator($this)->toString());
//    }
//
//    public static function provEvaluate(): iterable
//    {
//        $fooFOO = function (TestCase $test) {
//            return self::createArrayValuesIdentityConstraint($test, ['foo' => 'FOO']);
//        };
//        $gezGEZ = function (TestCase $test) {
//            return self::createArrayValuesIdentityConstraint($test, ['gez' => 'GEZ']);
//        };
//
//        // an unary constraint, always false
//        $unaryOp = function (TestCase $test) use ($fooFOO) {
//            return $test->getMockBuilder(UnaryOperator::class)
//                ->setConstructorArgs([$fooFOO($test)])
//                ->getMockForAbstractClass()
//            ;
//        };
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => $fooFOO,
//            'args'       => [['foo' => 'FOO', 'bar' => 'BAR'], '', true],
//            'expect'     => true,
//        ];
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => $gezGEZ,
//            'args'       => [['foo' => 'FOO', 'bar' => 'BAR'], '', true],
//            'expect'     => false,
//        ];
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => $gezGEZ,
//            'args'       => [123, '', true],
//            'expect'     => false,
//        ];
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => $fooFOO,
//            'args'       => [['foo' => 'FOO', 'bar' => 'BAR'], '', false],
//            'expect'     => null,
//        ];
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => $gezGEZ,
//            'args'       => [['foo' => 'FOO', 'bar' => 'BAR']],
//            'expect'     => [
//                'exception' => ExpectationFailedException::class,
//                'message'   => 'array is an array or ArrayAccess with values identical to specified',
//            ],
//        ];
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => $gezGEZ,
//            'args'       => [123],
//            'expect'     => [
//                'exception' => ExpectationFailedException::class,
//                'message'   => '123 is an array or ArrayAccess with values identical to specified',
//            ],
//        ];
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => $gezGEZ,
//            'args'       => [new \stdClass()],
//            'expect'     => [
//                'exception' => ExpectationFailedException::class,
//                'message'   => 'object stdClass is an array or ArrayAccess with values identical to specified',
//            ],
//        ];
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => $gezGEZ,
//            'args'       => [\stdClass::class],
//            'expect'     => [
//                'exception' => ExpectationFailedException::class,
//                'message'   => 'stdClass is an array or ArrayAccess with values identical to specified',
//            ],
//        ];
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => $gezGEZ,
//            'args'       => ['foo'],
//            'expect'     => [
//                'exception' => ExpectationFailedException::class,
//                'message'   => '\'foo\' is an array or ArrayAccess with values identical to specified',
//            ],
//        ];
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => function (TestCase $test) use ($fooFOO): Constraint {
//                return self::logicalNot($fooFOO($test));
//            },
//            'args'   => [['foo' => 'FOO', 'bar' => 'BAR']],
//            'expect' => [
//                'exception' => ExpectationFailedException::class,
//                'message'   => 'array fails to be an array or ArrayAccess with values identical to specified',
//            ],
//        ];
//
//        yield 'AbstractConstraintTest.php:'.__LINE__ => [
//            'constraint' => $unaryOp,
//            'args'       => [['foo' => 'FOO', 'bar' => 'BAR']],
//            'expect'     => [
//                'exception' => ExpectationFailedException::class,
//                'message'   => 'is an array or ArrayAccess with values identical to specified',
//            ],
//        ];
//    }
//
//    /**
//     * @dataProvider provEvaluate
//     *
//     * @param mixed $expect
//     *
//     * @psalm-param \Closure(TestCase):Constraint $constraint
//     * @psalm-param non-empty-list                $args
//     */
//    public function testEvaluate(\Closure $constraint, array $args, $expect): void
//    {
//        if (is_array($expect)) {
//            $this->expectException($expect['exception']);
//            $this->expectExceptionMessage($expect['message']);
//        }
//
//        $actual = $constraint($this)->evaluate(...$args);
//
//        if (!is_array($expect)) {
//            $this->assertSame($expect, $actual);
//        }
//    }
}
// vim: syntax=php sw=4 ts=4 et:
