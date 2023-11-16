<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Operator;
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\ComparatorWrapperInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\AbstractConstraint
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class AbstractConstraintTest extends TestCase
{
    public static function createConstraintMock(
        TestCase $test,
        ComparatorInterface $comparator = null,
        SelectionInterface $expected = null,
        RecursiveUnwrapperInterface $unwrapper = null
    ) {
        if (null === $comparator) {
            $comparator = $test->createMock(ComparatorInterface::class);
        }

        if (null === $expected) {
            $expected = $test->createMock(SelectionInterface::class);
        }

        if (null === $unwrapper) {
            $unwrapper = $test->createMock(RecursiveUnwrapperInterface::class);
        }

        $mock = $test->getMockBuilder(AbstractConstraint::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;

        // AbstractConstraint::__construct() is protected, but we need it
        $class = new \ReflectionClass(AbstractConstraint::class);
        $construct = $class->getMethod('__construct');
        $construct->setAccessible(true);
        $construct->invokeArgs($mock, [$comparator, $expected, $unwrapper]);

        return $mock;
    }

    public static function createArrayValuesIdentityConstraint(TestCase $test, array $expected)
    {
        return self::createConstraintMock(
            $test,
            new IdentityComparator(),
            new Selection(new ArrayValueSelector(), $expected),
            new RecursiveUnwrapper()
        );
    }

    //
    //
    // TESTS
    //
    //

    public function testExtendsConstraint(): void
    {
        $constraint = self::createConstraintMock($this);
        $this->assertInstanceOf(Constraint::class, $constraint);
    }

    public function testImplementsComparatorWrapperInterface(): void
    {
        $constraint = self::createConstraintMock($this);
        $this->assertInstanceOf(ComparatorWrapperInterface::class, $constraint);
    }

    public function testImplementsSelectionWrapperInterface(): void
    {
        $constraint = self::createConstraintMock($this);
        $this->assertInstanceOf(SelectionWrapperInterface::class, $constraint);
    }

    public function testConstruct(): void
    {
        $comparator = $this->createMock(ComparatorInterface::class);
        $expected = $this->createMock(SelectionInterface::class);

        $constraint = self::createConstraintMock($this, $comparator, $expected);

        $this->assertSame($comparator, $constraint->getComparator());
        $this->assertSame($expected, $constraint->getSelection());
    }

    public function testToString(): void
    {
        $comparator = $this->createMock(ComparatorInterface::class);
        $expected = $this->createMock(SelectionInterface::class);

        $selector = $this->createMock(ValueSelectorInterface::class);

        $expected->expects($this->any())
            ->method('getSelector')
            ->willReturn($selector)
        ;

        $selector->expects($this->once())
            ->method('subject')
            ->willReturn('a tree')
        ;

        $selector->expects($this->once())
            ->method('selectable')
            ->willReturn('apples')
        ;

        $comparator->expects($this->once())
            ->method('adjective')
            ->willReturn('having colors')
        ;

        $constraint = self::createConstraintMock($this, $comparator, $expected);

        $this->assertSame('is a tree with apples having colors specified', $constraint->toString());
    }

    public static function provToStringInContext(): array
    {
        $constraint = function (TestCase $test): Constraint {
            $comparator = $test->createMock(ComparatorInterface::class);
            $expected = $test->createMock(SelectionInterface::class);

            $selector = $test->createMock(ValueSelectorInterface::class);

            $expected->expects($test->any())
                ->method('getSelector')
                ->willReturn($selector)
            ;

            $selector->expects($test->any())
                ->method('subject')
                ->willReturn('a tree')
            ;

            $selector->expects($test->any())
                ->method('selectable')
                ->willReturn('apples')
            ;

            $comparator->expects($test->any())
                ->method('adjective')
                ->willReturn('having colors')
            ;

            return static::createConstraintMock($test, $comparator, $expected);
        };

        return [
            'AbstractConstraintTest.php:'.__LINE__ => [
                'operator' => fn (TestCase $test): Operator => self::logicalNot($constraint($test)),
                'expect'   => 'fails to be a tree with apples having colors specified',
            ],
            'AbstractConstraintTest.php:'.__LINE__ => [
                'operator' => fn (TestCase $test): Operator => self::logicalOr($constraint($test)),
                'expect'   => 'is a tree with apples having colors specified',
            ],
        ];
    }

    /**
     * @param \Closure(TestCase $test): Operator
     *
     * @dataProvider provToStringInContext
     */
    public function testToStringInContext(\Closure $operator, string $expect): void
    {
        $this->assertSame($expect, $operator($this)->toString());
    }

    public static function provEvaluate(): array
    {
        $fooFOO = fn (TestCase $test) => self::createArrayValuesIdentityConstraint($test, ['foo' => 'FOO']);
        $gezGEZ = fn (TestCase $test) => self::createArrayValuesIdentityConstraint($test, ['gez' => 'GEZ']);

        // an unary constraint, always false
        $unaryOp = fn (TestCase $test) => $test->getMockBuilder(UnaryOperator::class)
            ->setConstructorArgs([$fooFOO($test)])
            ->getMockForAbstractClass()
        ;

        return [
            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => $fooFOO,
                'args'       => [['foo' => 'FOO', 'bar' => 'BAR'], '', true],
                'expect'     => true,
            ],

            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => $gezGEZ,
                'args'       => [['foo' => 'FOO', 'bar' => 'BAR'], '', true],
                'expect'     => false,
            ],

            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => $gezGEZ,
                'args'       => [123, '', true],
                'expect'     => false,
            ],

            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => $fooFOO,
                'args'       => [['foo' => 'FOO', 'bar' => 'BAR'], '', false],
                'expect'     => null,
            ],

            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => $gezGEZ,
                'args'       => [['foo' => 'FOO', 'bar' => 'BAR']],
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => 'array is an array or ArrayAccess with values identical to specified',
                ],
            ],

            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => $gezGEZ,
                'args'       => [123],
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => '123 is an array or ArrayAccess with values identical to specified',
                ],
            ],

            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => $gezGEZ,
                'args'       => [new \stdClass()],
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => 'object stdClass is an array or ArrayAccess with values identical to specified',
                ],
            ],

            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => $gezGEZ,
                'args'       => [\stdClass::class],
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => 'stdClass is an array or ArrayAccess with values identical to specified',
                ],
            ],

            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => $gezGEZ,
                'args'       => ['foo'],
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => '\'foo\' is an array or ArrayAccess with values identical to specified',
                ],
            ],

            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => fn (TestCase $test): Constraint => self::logicalNot($fooFOO($test)),
                'args'       => [['foo' => 'FOO', 'bar' => 'BAR']],
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => 'array fails to be an array or ArrayAccess with values identical to specified',
                ],
            ],

            'AbstractConstraintTest.php:'.__LINE__ => [
                'constraint' => $unaryOp,
                'args'       => [['foo' => 'FOO', 'bar' => 'BAR']],
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => 'is an array or ArrayAccess with values identical to specified',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provEvaluate
     *
     * @param \Closure(TestCase):Constraint $constraint
     * @param mixed                         $expect
     */
    public function testEvaluate(\Closure $constraint, array $args, $expect): void
    {
        if (is_array($expect)) {
            $this->expectException($expect['exception']);
            $this->expectExceptionMessage($expect['message']);
        }

        $actual = $constraint($this)->evaluate(...$args);

        if (!is_array($expect)) {
            $this->assertSame($expect, $actual);
        }
    }
}
// vim: syntax=php sw=4 ts=4 et:
