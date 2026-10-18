<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Tailors\PHPUnit\Common\AbstractArrayObject;
use Tailors\PHPUnit\Common\AbstractArrayObjectTestCase;
use Tailors\PHPUnit\Common\ExportableNameInterface;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractArrayObjectTestCase
 */
#[CoversClass(AbstractArrayValues::class)]
#[CoversClass(AbstractArrayValuesTest::class)]
#[CoversClass(AbstractArrayObjectTestCase::class)]
#[Small]
final class AbstractArrayValuesTest extends AbstractArrayObjectTestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExtendsAbstractArrayObject(): void
    {
        self::assertInstanceOf(AbstractArrayObject::class, self::getObject([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsTagInterface(): void
    {
        self::assertInstanceOf(TagInterface::class, self::getObject([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsExportableNameInterface(): void
    {
        self::assertInstanceOf(ExportableNameInterface::class, self::getObject([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testTag(): void
    {
        $tag = StaticRandomStrings::familyTag(__NAMESPACE__.'\ArrayValues', 'c225435bd5434279f77fb3cddf138302a5c826ec');
        self::assertSame($tag, self::getObject([])->tag());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExportableName(): void
    {
        self::assertSame('ArrayValues', AbstractArrayValues::exportableName());
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return AbstractArrayValues
     *
     * @codeCoverageIgnore
     */
    public static function getObject(array $ctorArgs): iterable
    {
        return new class(...$ctorArgs) extends AbstractArrayValues {};
    }
}

// vim: syntax=php sw=4 ts=4 et:
