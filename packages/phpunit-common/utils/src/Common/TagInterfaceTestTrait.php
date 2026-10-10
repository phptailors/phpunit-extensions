<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\TestCase;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-require-extends TestCase
 *
 * @var TestCase $__phpactor__workaround__unused_import__TestCase
 */
trait TagInterfaceTestTrait
{
    /**
     * @psalm-return iterable<string, array{
     *  object: TagInterface,
     *  tag: mixed
     * }>
     */
    abstract public static function provTag(): iterable;

    /**
     * @psalm-return iterable<string, array{object: object}>
     */
    abstract public static function provImplementsTagInterface(): iterable;

    /**
     * @dataProvider provTag
     *
     * @param mixed $tag
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testTag(TagInterface $object, $tag): void
    {
        self::assertSame($tag, $object->tag());
    }

    /**
     * @dataProvider provImplementsTagInterface
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsTagInterface(object $object): void
    {
        self::assertInstanceOf(TagInterface::class, $object);
    }
}
