<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Properties\AbstractConstraint;
use Tailors\PHPUnit\Properties\ExpectedPropertiesInterface;
use Tailors\PHPUnit\Properties\RecursivePropertiesUnwrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
abstract class PropertiesConstraintTestCase extends TestCase
{
    use ExtendsClassTrait;
    use ImplementsInterfaceTrait;

    abstract public static function subject(): string;

    abstract public static function adjective(): string;

    abstract public static function constraintClass(): string;

    abstract public static function comparatorClass(): string;

    public function testExtendsAbstractConstraint(): void
    {
        $class = static::constraintClass();
        self::assertExtendsClass(AbstractConstraint::class, $class);
    }

    public function testImplementsExpectedPropertiesInterface(): void
    {
        $class = static::constraintClass();
        self::assertImplementsInterface(ExpectedPropertiesInterface::class, $class);
    }

    // @codeCoverageIgnoreStart
    public function provCreate(): array
    {
        $unwrapper = $this->createMock(RecursivePropertiesUnwrapperInterface::class);

        return [
            'PropertiesConstraintTestTrait.php:'.__LINE__ => [
                'args'   => [['foo' => 'FOO']],
                'expect' => [
                    'properties' => self::identicalTo(['foo' => 'FOO']),
                ],
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provCreate
     */
    public function testCreate(array $args, array $expect): void
    {
        $class = static::constraintClass();
        $constraint = $class::create(...$args);
        self::assertThat($constraint->getArrayCopy(), $expect['properties']);
    }

    // @codeCoverageIgnoreStart
    public static function provArrayWithNonStringKeys(): array
    {
        return [
            'PropertiesConstraintTestTrait.php:'.__LINE__ => [
                'array' => [
                    'a' => 'A',
                    0   => 'B',
                ],
                'count' => 1,
            ],
            'PropertiesConstraintTestTrait.php:'.__LINE__ => [
                'array' => [
                    'a' => 'A',
                    0   => 'B',
                    2   => 'C',
                    7   => 'D',
                    'e' => 'E',
                ],
                'count' => 3,
            ],
        ];
    }

    /**
     * @dataProvider provArrayWithNonStringKeys
     */
    final public function testCreateWithNonStringKeys(array $array, int $count): void
    {
        $this->examineExceptionOnNonStringKeys($array, $count);
    }

    // @codeCoverageIgnoreEnd

    final public function testFailureExceptionInUnaryOperatorContext(): void
    {
        $class = static::constraintClass();
        $constraint = $class::create([]);

        $unary = $this->wrapWithUnaryOperator($constraint);
        $verbAndAdjective = sprintf('is %s', static::subject());

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage(self::message('null', $verbAndAdjective, static::adjective()));

        self::assertThat(null, $unary);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * Returns $constraint wrapped with UnaryOperator mock.
     */
    final protected function wrapWithUnaryOperator(
        Constraint $constraint,
        string $operator = 'noop',
        int $precedence = 1
    ): UnaryOperator {
        $unary = $this->getMockBuilder(UnaryOperator::class)
            ->setConstructorArgs([$constraint])
            ->getMockForAbstractClass()
        ;

        $unary->expects(self::any())
            ->method('operator')
            ->willReturn($operator)
        ;
        $unary->expects(self::any())
            ->method('precedence')
            ->willReturn($precedence)
        ;

        return $unary;
    }

    /**
     * @param mixed $actual
     */
    final protected function examinePropertiesMatchSucceeds(array $expect, $actual): void
    {
        $class = static::constraintClass();
        $constraint = $class::create($expect);
        self::assertThat($actual, $constraint);
    }

    /**
     * @param mixed $actual
     */
    final protected function examinePropertiesMatchFails(array $expect, $actual, string $string): void
    {
        $class = static::constraintClass();
        $constraint = $class::create($expect);
        $message = self::message($string, 'is '.static::subject(), static::adjective());

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @param mixed $actual
     */
    final protected function examineNotPropertiesMatchSucceeds(array $expect, $actual): void
    {
        $class = static::constraintClass();
        $constraint = self::logicalNot($class::create($expect));
        self::assertThat($actual, $constraint);
    }

    /**
     * @param mixed $actual
     */
    final protected function examineNotPropertiesMatchFails(array $expect, $actual, string $string): void
    {
        $class = static::constraintClass();
        $constraint = self::logicalNot($class::create($expect));
        $message = self::message($string, 'fails to be '.static::subject(), static::adjective());

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * Assembles expected failure message out of pieces.
     *
     * @param string $value          A noun representing the actual value, such as "123" or "array" or "object stdClass"
     * @param string $verbAndSubject A concatenated verb and subject, such as "is a class", or "fails to be an object"
     * @param string $adjective      An adjective reflecting the comparison: "equal to" or "identical to"
     */
    final protected static function message(string $value, string $verbAndSubject, string $adjective): string
    {
        return sprintf('Failed asserting that %s.', self::statement($value, $verbAndSubject, $adjective));
    }

    /**
     * Assembles a statement which is a part of failure message.
     *
     * @param string $value          A noun representing the actual value, such as "123" or "array" or "object stdClass"
     * @param string $verbAndSubject A concatenated verb and subject, such as "is a class", or "fails to be an object"
     * @param string $adjective      An adjective reflecting the comparison: "equal to" or "identical to"
     */
    final protected static function statement(string $value, string $verbAndSubject, string $adjective): string
    {
        return sprintf('%s %s with properties %s specified', $value, $verbAndSubject, $adjective);
    }

    /**
     * Assert that $function throws InvalidArgumentException with appropriate
     * message when provided with an array having one or more non-string keys.
     *
     * @param array    $array    An array with non-string keys to be passed as an argument to $function
     * @param int      $count    Number of non-string keys in $array
     * @param callable $function A function that creates constraint
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    private function examineExceptionOnNonStringKeys(array $array, int $count, callable $function = null): void
    {
        if (null === $function) {
            $function = [static::constraintClass(), 'create'];
        }

        $message = sprintf(
            'Argument #1 of %s::create() must be an associative array with string keys, '.
            'an array with %d non-string %s given',
            static::constraintClass(),
            $count,
            $count > 1 ? 'keys' : 'key'
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        call_user_func($function, $array);

        // @codeCoverageIgnoreStart
    }
}

// vim: syntax=php sw=4 ts=4 et:
