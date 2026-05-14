<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\DataProvider;
use Tailors\PHPUnit\Common\StaticRandomStrings;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait GenericValuesTestTrait
{
    abstract public static function getValuesClass(): string;

    //
    //
    // TESTS
    //
    //

    public function testImplementsValuesInterface(): void
    {
        $class = self::getValuesClass();
        self::assertInstanceOf(ValuesInterface::class, new $class());
    }

    public function testExtendsArrayObject(): void
    {
        $class = self::getValuesClass();
        self::assertInstanceOf(\ArrayObject::class, new $class());
    }

    // @codeCoverageIgnoreStart
    public static function provValues(): iterable
    {
        // #0
        yield 'GenericValuesTestTrait.php:'.__LINE__ => [
            'args'   => [],
            'expect' => [],
        ];

        // #1
        yield 'GenericValuesTestTrait.php:'.__LINE__ => [
            'args'   => [[]],
            'expect' => [],
        ];

        // #2
        yield 'GenericValuesTestTrait.php:'.__LINE__ => [
            'args'   => [['foo' => 'FOO']],
            'expect' => ['foo' => 'FOO'],
        ];

        // #3
        yield 'GenericValuesTestTrait.php:'.__LINE__ => [
            'args'   => [new \ArrayObject(['foo' => 'FOO'])],
            'expect' => ['foo' => 'FOO'],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @psalm-param list{0?:array|\Traversable} $args
     */
    #[DataProvider('provValues')]
    public function testValues(array $args, mixed $expect): void
    {
        $class = self::getValuesClass();
        $object = new $class(...$args);

        self::assertSame($expect, iterator_to_array($object));
        self::assertSame($expect, (array) $object);
        self::assertSame(ActualValues::class === $class, $object->actual());
    }

    // @codeCoverageIgnoreStart
    public static function provTag(): iterable
    {
        $family = __NAMESPACE__.'\GenericValues';
        $familyHex = StaticRandomStrings::get($family);
        $familyTag = "{$family}:{$familyHex}";

        // #0
        yield 'GenericValuesTestTrait.php:'.__LINE__ => [
            'args'   => [],
            'expect' => $familyTag,
        ];

        // #1
        yield 'GenericValuesTestTrait.php:'.__LINE__ => [
            'args'   => [['foo' => 'FOO']],
            'expect' => $familyTag,
        ];

        // #2
        yield 'GenericValuesTestTrait.php:'.__LINE__ => [
            'args'   => [['foo' => 'FOO'], 'TAGFOO'],
            'expect' => 'TAGFOO',
        ];
    }
    // @codeCoverageIgnoreEnd

    /**
     * @psalm-param list{0?:array|\Traversable,1?:null|non-empty-string} $args
     */
    #[DataProvider('provTag')]
    public function testTag(array $args, mixed $expect): void
    {
        $class = self::getValuesClass();
        $object = new $class(...$args);

        $this->assertSame($expect, $object->tag());
    }

    public function testCreateActualValues(): void
    {
        $class = self::getValuesClass();
        $object = new $class([], 'TAGFOO');
        $actual = $object->createActualValues(['foo' => 'FOO']);

        $this->assertSame(['foo' => 'FOO'], (array) $actual);
        $this->assertSame('TAGFOO', $actual->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
