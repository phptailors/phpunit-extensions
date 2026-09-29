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
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\SelfDescribing;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ArraySpec\DummyValueSelectorAndResultFactoryWrapper;
use Tailors\PHPUnit\ArraySpec\DummyValueSelectorWrapper;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\EqualityComparator;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveActualResultFactoryVisitor;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveExpectedResultFactoryVisitor;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactory;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryInterface;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperInterface;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperVisitor;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal;
use Tailors\PHPUnit\ResultFactory\DummyArrayResultFactory;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;
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

    /**
     * @psalm-param array $expected
     */
    public static function createArrayValuesConstraint(
        array $expected,
        ComparatorInterface $comparator
    ): DummyAbstractRecursiveConstraint {
        $resultFactory = new DummyArrayResultFactory();
        $valueSelector = new DummyValueSelector(
            function ($subject): bool {
                return is_array($subject);
            },
            function ($subject, $key, &$retval): bool {
                if (!array_key_exists($key, $subject)) {
                    return false;
                }
                $retval = $subject[$key];

                return true;
            },
            'an array',
            'values'
        );

        return DummyAbstractRecursiveConstraint::create(
            new DummyValueSelectorAndResultFactoryWrapper($resultFactory, $valueSelector, $expected),
            $comparator,
            new RecursiveResultFactory(
                new RecursiveExpectedResultFactoryVisitor(),
                new RecursiveActualResultFactoryVisitor(),
                new RecursiveTraversal()
            ),
            new RecursiveResultUnwrapper(
                new RecursiveResultUnwrapperVisitor(),
                new RecursiveTraversal()
            )
        );
    }

    /**
     * @psalm-param array $expected
     */
    public static function createArrayValuesIdentityConstraint(array $expected): DummyAbstractRecursiveConstraint
    {
        return self::createArrayValuesConstraint($expected, new IdentityComparator());
    }

    /**
     * @psalm-param array $expected
     */
    public static function createArrayValuesEqualityConstraint(array $expected): DummyAbstractRecursiveConstraint
    {
        return self::createArrayValuesConstraint($expected, new EqualityComparator());
    }

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

        $expected = new DummyValueSelectorWrapper($valueSelector, $this->createMock(\Traversable::class));

        $comparator->expects($this->once())
            ->method('adjective')
            ->willReturn('having colors')
        ;

        $constraint = $this->createDummyConstraint($expected, $comparator);

        $this->assertSame('is a tree with apples having colors specified', $constraint->toString());
    }

    /**
     * @return iterable<string,array{
     *      subject: string,
     *      selectable: string,
     *      adjective: string,
     *      operator: \Closure(Constraint):SelfDescribing,
     *      expect: mixed
     * }>
     */
    public static function provToStringInContext(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'subject'    => 'a tree',
            'selectable' => 'apples',
            'adjective'  => 'having colors',
            'operator'   => function (Constraint $constraint): SelfDescribing {
                return $constraint;
            },
            'expect'     => 'is a tree with apples having colors specified',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject'    => 'a tree',
            'selectable' => 'apples',
            'adjective'  => 'having colors',
            'operator'   => function (Constraint $constraint): SelfDescribing {
                return self::logicalNot($constraint);
            },
            'expect'     => 'fails to be a tree with apples having colors specified',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject'    => 'a tree',
            'selectable' => 'apples',
            'adjective'  => 'having colors',
            'operator'   => function (Constraint $constraint): SelfDescribing {
                return self::logicalOr($constraint);
            },
            'expect'     => 'is a tree with apples having colors specified',
        ];
    }

    /**
     * @dataProvider provToStringInContext
     *
     * @param mixed $expect
     *
     * @psalm-param \Closure(Constraint):SelfDescribing $operator
     */
    public function testToStringInContext(string $subject, string $selectable, string $adjective, \Closure $operator, $expect): void
    {
        $valueSelector = $this->createMock(ValueSelectorInterface::class);

        $valueSelector->expects($this->any())
            ->method('subject')
            ->willReturn($subject)
        ;

        $valueSelector->expects($this->any())
            ->method('selectable')
            ->willReturn($selectable)
        ;

        $array = $this->createMock(\Traversable::class);
        $expected = new DummyValueSelectorWrapper($valueSelector, $array);

        $comparator = $this->createMock(ComparatorInterface::class);
        $comparator->expects($this->any())
            ->method('adjective')
            ->willReturn($adjective)
        ;

        $constraint = $this->createDummyConstraint($expected, $comparator);

        $this->assertSame($expect, $operator($constraint)->toString());
    }

    /**
     * @psalm-return iterable<string, array{
     *      constraint: Constraint,
     *      args: non-empty-list,
     *      expect: mixed
     * }>
     */
    public static function provEvaluate(): iterable
    {
        $foo = self::createArrayValuesIdentityConstraint(['foo' => 'FOO']);
        $bar = self::createArrayValuesIdentityConstraint(['bar' => 'BAR']);
        $baz = self::createArrayValuesEqualityConstraint(['baz' => 123]);

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $foo,
            'args'       => [['foo' => 'FOO', 'bar' => 'BAR'], '', true],
            'expect'     => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $bar,
            'args'       => [['foo' => 'FOO', 'gez' => 'GEZ'], '', true],
            'expect'     => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $baz,
            'args'       => [['foo' => 'FOO', 'baz' => '123'], '', true],
            'expect'     => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $baz,
            'args'       => [['foo' => 'FOO', 'baz' => 123], '', true],
            'expect'     => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $baz,
            'args'       => [['123'], '', true],
            'expect'     => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $baz,
            'args'       => [['baz' => 321], '', true],
            'expect'     => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $bar,
            'args'       => [123, '', true],
            'expect'     => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $foo,
            'args'       => [['foo' => 'FOO', 'bar' => 'BAR'], '', false],
            'expect'     => null,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $bar,
            'args'       => [['foo' => 'FOO', 'gez' => 'GEZ']],
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => 'array is an array with values identical to specified',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $bar,
            'args'       => [123],
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => '123 is an array with values identical to specified',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $bar,
            'args'       => [new \stdClass()],
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => 'object stdClass is an array with values identical to specified',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $bar,
            'args'       => [\stdClass::class],
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => 'stdClass is an array with values identical to specified',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $bar,
            'args'       => ['foo'],
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => '\'foo\' is an array with values identical to specified',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => self::logicalNot($foo),
            'args'       => [['foo' => 'FOO', 'bar' => 'BAR']],
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => 'array fails to be an array with values identical to specified',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $baz,
            'args'       => ['foo'],
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => '\'foo\' is an array with values equal to specified',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => new class($foo) extends UnaryOperator {
                public function operator(): string
                {
                    return '';
                }

                public function precedence(): int
                {
                    return 0;
                }
            },
            'args'       => [['foo' => 'FOO', 'bar' => 'BAR']],
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => 'is an array with values identical to specified',
            ],
        ];
    }

    /**
     * @dataProvider provEvaluate
     *
     * @param mixed $expect
     *
     * @psalm-param Constraint     $constraint
     * @psalm-param non-empty-list $args
     */
    public function testEvaluate(Constraint $constraint, array $args, $expect): void
    {
        if (is_array($expect)) {
            $this->expectException($expect['exception']);
            $this->expectExceptionMessage($expect['message']);
        }

        $actual = $constraint->evaluate(...$args);

        if (!is_array($expect)) {
            $this->assertSame($expect, $actual);
        }
    }
}
// vim: syntax=php sw=4 ts=4 et:
