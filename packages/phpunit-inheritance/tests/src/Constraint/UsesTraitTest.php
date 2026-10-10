<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Examples\Inheritance\ExampleClassNotUsingTrait;
use Tailors\PHPUnit\Examples\Inheritance\ExampleClassUsingTrait;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTraitUsingTrait;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\UsesTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ExpectExceptionArray = array{exception: class-string<\Exception>, message: string}
 */
final class UsesTraitTest extends TestCase
{
    use InheritanceConstraintTestTrait;

    /**
     * @psalm-return iterable<string, array{
     *      constraint: Constraint,
     *      subject: mixed,
     *      expect: ExpectExceptionArray
     * }>
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public static function provFailureDescriptionOfCustomUnaryOperator(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => UsesTrait::create(ExampleTrait::class),
            'subject'    => \Exception::class,
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => '/Exception uses trait '.preg_quote(ExampleTrait::class, '/').'/',
            ],
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      constraint: Constraint,
     *      subject: mixed,
     *      expect: ExpectExceptionArray
     * }>
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public static function provFailureDescriptionOfLogicalNotOperator(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => UsesTrait::create(ExampleTrait::class),
            'subject'    => ExampleClassUsingTrait::class,
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => sprintf(
                    '/%s does not use trait %s/',
                    preg_quote(ExampleClassUsingTrait::class, '/'),
                    preg_quote(ExampleTrait::class, '/')
                ),
            ],
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      trait: string,
     *      subject: mixed
     * }>
     */
    public static function provUsesTrait(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => ExampleTrait::class,
            'subject' => ExampleClassUsingTrait::class,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => ExampleTrait::class,
            'subject' => new ExampleClassUsingTrait(),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => ExampleTrait::class,
            'subject' => ExampleTraitUsingTrait::class,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => 'tailors\\PhPunit\\eXamples\\inhEritance\eXampletRait',
            'subject' => 'tailors\\PhPunit\\eXamples\\inhEritance\eXampleclAssuSingtRait',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => 'tailors\\PhPunit\\eXamples\\inhEritance\eXampletRait',
            'subject' => new ExampleClassUsingTrait(),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => 'tailors\\PhPunit\\eXamples\\inhEritance\eXampletRait',
            'subject' => 'tailors\\PhPunit\\eXamples\\inhEritance\eXampletRaituSingtRait',
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      trait: string,
     *      subject: mixed,
     *      message: string
     * }>
     */
    public static function provNotUsesTrait(): iterable
    {
        $template = 'Failed asserting that %s uses trait %s.';

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => ExampleTrait::class,
            'subject' => ExampleClassNotUsingTrait::class,
            'message' => sprintf($template, ExampleClassNotUsingTrait::class, ExampleTrait::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => ExampleTrait::class,
            'subject' => new ExampleClassNotUsingTrait(),
            'message' => sprintf($template, 'object '.ExampleClassNotUsingTrait::class, ExampleTrait::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => ExampleTrait::class,
            'subject' => 'lorem ipsum',
            'message' => sprintf($template, "'lorem ipsum'", ExampleTrait::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => ExampleTrait::class,
            'subject' => 123,
            'message' => sprintf($template, '123', ExampleTrait::class),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      argument: string,
     *      message: string
     * }>
     */
    public static function provConstraintThrowsInvalidArgumentException(): iterable
    {
        $message = '/Argument 1 passed to \S+ must be a trait-string/';

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => 'non-trait string',
            'message'  => $message,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => \Exception::class,
            'message'  => $message,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => \Throwable::class,
            'message'  => $message,
        ];
    }

    /**
     * @dataProvider provUsesTrait
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstraintSucceeds(string $trait, $subject): void
    {
        $constraint = UsesTrait::create($trait);

        self::assertTrue($constraint->evaluate($subject, '', true));
    }

    /**
     * @dataProvider provNotUsesTrait
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstraintFails(string $trait, $subject, string $message): void
    {
        $constraint = UsesTrait::create($trait);

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        $constraint->evaluate($subject);
    }

    /**
     * @dataProvider provConstraintThrowsInvalidArgumentException
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstraintThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageMatches($message);

        UsesTrait::create($argument);
    }
}
