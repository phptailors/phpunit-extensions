<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Inheritance;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs       = list{string, string, string, Closure(string):array<string,string>, Closure(string):bool}
 * @psalm-type AssertTestCall = array{other: mixed, exception?: string}
 */
#[CoversClass(AbstractInheritanceConstraint::class)]
#[CoversClass(AbstractInheritanceConstraintTest::class)]
#[Small]
final class AbstractInheritanceConstraintTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsConstraint(): void
    {
        $constraint = $this->createStub(AbstractInheritanceConstraint::class);
        self::assertInstanceOf(Constraint::class, $constraint);
    }

    /**
     * @psalm-return iterable<string, array{
     *      constraint: Constraint,
     *      assertion: AssertTestCall
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provAbstractInheritanceConstraint(): iterable
    {
        $class_parents = function (string $class): array {
            $value = class_parents($class);

            return false === $value ? [] : $value;
        };

        $class_implements = function (string $class): array {
            $value = class_implements($class);

            return false === $value ? [] : $value;
        };

        $class_exists = (fn (string $subject): bool => class_exists($subject));

        $extendsException = self::getObject([\Exception::class, 'extends', 'does not extend', $class_parents, $class_exists]);
        $implementsThrowable = self::getObject([\Throwable::class, 'implements', 'does not implement', $class_implements, $class_exists]);

        $unoExtendsException = new class($extendsException) extends UnaryOperator {
            public function operator(): string
            {
                return 'uno';
            }

            public function precedence(): int
            {
                return 5;
            }

            protected function matches(mixed $other): bool
            {
                /**
                 * @var bool
                 *
                 * @psalm-suppress MissingThrowsDocblock
                 */
                return $this->constraint()->evaluate($other, '', true);
            }
        };

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $extendsException,
            'assertion'  => [
                'other'     => null,
                'exception' => 'Failed asserting that null extends Exception',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $extendsException,
            'assertion'  => [
                'other'     => 'lorem ipsum',
                'exception' => 'Failed asserting that \'lorem ipsum\' extends Exception',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $extendsException,
            'assertion'  => [
                'other'     => \stdClass::class,
                'exception' => 'Failed asserting that stdClass extends Exception',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $implementsThrowable,
            'assertion'  => [
                'other'     => \stdClass::class,
                'exception' => 'Failed asserting that stdClass implements Throwable',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $extendsException,
            'assertion'  => [
                'other'     => new \stdClass(),
                'exception' => 'Failed asserting that object stdClass extends Exception',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $extendsException,
            'assertion'  => [
                'other' => \RuntimeException::class,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $extendsException,
            'assertion'  => [
                'other' => new \RuntimeException('lorem ipsum'),
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => self::logicalOr($extendsException, self::isTrue()),
            'assertion'  => [
                'other'     => null,
                'exception' => 'Failed asserting that null extends Exception or is true',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => self::logicalNot($extendsException),
            'assertion'  => [
                'other'     => new \RuntimeException('lorem ipsum'),
                'exception' => 'Failed asserting that object RuntimeException does not extend Exception',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => self::logicalNot($implementsThrowable),
            'assertion'  => [
                'other'     => new \Exception('lorem ipsum'),
                'exception' => 'Failed asserting that object Exception does not implement Throwable',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => $unoExtendsException,
            'assertion'  => [
                'other'     => \stdClass::class,
                'exception' => 'Failed asserting that stdClass extends Exception',
            ],
        ];
    }

    /**
     * @psalm-param AssertTestCall $assertion
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provAbstractInheritanceConstraint')]
    public function testAbstractInheritanceConstraint(Constraint $constraint, array $assertion): void
    {
        if (null !== ($exception = $assertion['exception'] ?? null)) {
            $this->expectException(ExpectationFailedException::class);
            $this->expectExceptionMessage($exception);
        }

        self::assertThat($assertion['other'], $constraint);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstruct(): void
    {
        $class_parents = function (string $class): array {
            $value = class_parents($class);

            return false === $value ? [] : $value;
        };

        $class_exists = (fn (string $subject): bool => class_exists($subject));

        $constraint = self::getObject([\Exception::class, 'extends', 'does not extend', $class_parents, $class_exists]);

        self::assertThat(\RuntimeException::class, $constraint);
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     */
    private static function getObject(array $ctorArgs): AbstractInheritanceConstraint
    {
        return new class(...$ctorArgs) extends AbstractInheritanceConstraint {
            /**
             * @psalm-param Closure(string):array<string, string> $inheritance
             * @psalm-param Closure(string):bool                  $supports
             */
            public function __construct(
                string $expects,
                public readonly string $verb,
                public readonly string $negatedVerb,
                public readonly \Closure $inheritance,
                public readonly \Closure $supports
            ) {
                parent::__construct($expects);
            }

            protected function verb(): string
            {
                return $this->verb;
            }

            protected function negatedVerb(): string
            {
                return $this->negatedVerb;
            }

            /**
             * @psalm-return array<string, string>
             */
            protected function inheritance(string $class): array
            {
                return call_user_func($this->inheritance, $class);
            }

            protected function supports(string $subject): bool
            {
                return call_user_func($this->supports, $subject);
            }
        };
    }
}
