<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Arrays\ArrayValuesSelection;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayValuesTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type ArrayValuesArgs = list{ArrayLike}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ArrayValuesTraitTest extends TestCase
{
    use ArrayValuesTrait;

    /**
     * @psalm-return iterable<string,array{args: ArrayValuesArgs, expect: mixed}>
     */
    public static function provExpectArrayValues(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'args'   => [[]],
            'expect' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'args'   => [['a' => 'A']],
            'expect' => ['a' => 'A'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'args'   => [new \ArrayObject(['o' => 'O'])],
            'expect' => ['o' => 'O'],
        ];
    }

    /**
     * @dataProvider provExpectArrayValues
     *
     * @param mixed $expect
     *
     * @psalm-param ArrayValuesArgs $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExpectedArrayValues(array $args, $expect): void
    {
        $values = self::arrayValues(...$args);
        self::assertInstanceOf(ArrayValuesSelection::class, $values);
        self::assertSame($expect, iterator_to_array($values));
    }
}

// vim: syntax=php sw=4 ts=4 et:
