<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Arrays\RecursivelyKsortedArrayExpectations;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type RecursivelyKsortedArrayArgs = list{0: ArrayLike, 1?: int}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
#[CoversClass(RecursivelyKsortedArrayTraitTest::class)]
#[Small]
final class RecursivelyKsortedArrayTraitTest extends TestCase
{
    use RecursivelyKsortedArrayTrait;

    /**
     * @psalm-return iterable<string,array{args: RecursivelyKsortedArrayArgs, expect: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provExpectRecursivelyKsortedArray(): iterable
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
     * @param mixed $expect
     *
     * @psalm-param RecursivelyKsortedArrayArgs $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provExpectRecursivelyKsortedArray')]
    public function testExpectedRecursivelyKsortedArray(array $args, $expect): void
    {
        $values = self::recursivelyKsortedArray(...$args);
        self::assertInstanceOf(RecursivelyKsortedArrayExpectations::class, $values);
        self::assertSame($expect, iterator_to_array($values));
    }
}

// vim: syntax=php sw=4 ts=4 et:
