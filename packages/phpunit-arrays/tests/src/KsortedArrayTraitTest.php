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
use Tailors\PHPUnit\Arrays\KsortedArrayExpectations;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\KsortedArrayTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type KsortedArrayArgs = list{0: ArrayLike, 1?: int}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class KsortedArrayTraitTest extends TestCase
{
    use KsortedArrayTrait;

    /**
     * @psalm-return iterable<string,array{args: KsortedArrayArgs, expect: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provExpectKsortedArray(): iterable
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

        yield basename(__FILE__).':'.__LINE__ => [
            'args'   => [['a' => 'A'], SORT_NUMERIC],
            'expect' => ['a' => 'A'],
        ];
    }

    /**
     * @dataProvider provExpectKsortedArray
     *
     * @param mixed $expect
     *
     * @psalm-param KsortedArrayArgs $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExpectedKsortedArray(array $args, $expect): void
    {
        $values = self::ksortedArray(...$args);
        self::assertInstanceOf(KsortedArrayExpectations::class, $values);
        self::assertSame($expect, iterator_to_array($values));
    }
}

// vim: syntax=php sw=4 ts=4 et:
