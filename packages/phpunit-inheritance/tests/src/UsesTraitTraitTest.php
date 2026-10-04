<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\UsesTrait;
use Tailors\PHPUnit\Examples\Inheritance\ExampleClassNotUsingTrait;
use Tailors\PHPUnit\Examples\Inheritance\ExampleClassUsingTrait;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTraitUsingTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\UsesTraitTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class UsesTraitTraitTest extends TestCase
{
    use UsesTraitTrait;

    /**
     * @psalm-return iterable<string, array{
     *      trait: string,
     *      subject: mixed,
     *      message: string
     * }>
     */
    public static function provUsesTrait(): iterable
    {
        $template = 'Failed asserting that %s does not use trait %s.';

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => ExampleTrait::class,
            'subject' => ExampleClassUsingTrait::class,
            'message' => sprintf($template, ExampleClassUsingTrait::class, ExampleTrait::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => ExampleTrait::class,
            'subject' => new ExampleClassUsingTrait(),
            'message' => sprintf($template, 'object '.ExampleClassUsingTrait::class, ExampleTrait::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'trait'   => ExampleTrait::class,
            'subject' => ExampleTraitUsingTrait::class,
            'message' => sprintf($template, ExampleTraitUsingTrait::class, ExampleTrait::class),
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
     * @dataProvider provUsesTrait
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertUsesTraitSucceeds(string $trait, $subject): void
    {
        self::assertUsesTrait($trait, $subject);
    }

    /**
     * @dataProvider provNotUsesTrait
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertUsesTraitFails(string $trait, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertUsesTrait($trait, $subject);
    }

    /**
     * @dataProvider provNotUsesTrait
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotUsesTraitSucceeds(string $trait, $subject): void
    {
        self::assertNotUsesTrait($trait, $subject);
    }

    /**
     * @dataProvider provUsesTrait
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotUsesTraitFails(string $trait, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertNotUsesTrait($trait, $subject);
    }

    /**
     * @dataProvider provUsesTrait
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUsesTrait(string $trait, $subject): void
    {
        self::assertThat($subject, self::usesTrait($trait));
    }

    /**
     * @dataProvider provNotUsesTrait
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotUsesTrait(string $trait, $subject): void
    {
        self::assertThat($subject, self::logicalNot(self::usesTrait($trait)));
    }

    /**
     * @psalm-return iterable<string, array{
     *      argument: string,
     *      message: string
     * }>
     */
    public static function provUsesTraitThrowsInvalidArgumentException(): iterable
    {
        $template = 'Argument 1 passed to %s::create() must be a trait-string';

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => 'non-trait string',
            'message'  => sprintf($template, UsesTrait::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => \Exception::class,
            'message'  => sprintf($template, UsesTrait::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => \Throwable::class,
            'message'  => sprintf($template, UsesTrait::class),
        ];
    }

    /**
     * @dataProvider provUsesTraitThrowsInvalidArgumentException
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUsesTraitThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        self::usesTrait($argument);
    }
}

// vim: syntax=php sw=4 ts=4 et:
