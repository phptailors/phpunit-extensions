<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ResultFactory;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Result\DummyResult;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ResultFactory\DummyResultFactory
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike   = iterable<array-key, mixed>
 */
final class DummyResultFactoryTest extends TestCase
{
    public function testImplementsResultFactoryInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryInterface::class, new DummyResultFactory(false));
    }

    /**
     * @psalm-return iterable<string, array{
     *      supports: bool|\Closure(mixed):bool,
     *      input: mixed,
     *      actual: bool,
     *      expect: array{
     *          supports: mixed
     *      }
     * }>
     */
    public static function provDummyResultFactory(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => true,
            'input' => null,
            'actual' => false,
            'expect' => [
                'supports' => true,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => false,
            'input' => null,
            'actual' => false,
            'expect' => [
                'supports' => false,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => true,
            'input' => null,
            'actual' => true,
            'expect' => [
                'supports' => true,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => false,
            'input' => null,
            'actual' => true,
            'expect' => [
                'supports' => false,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => function ($input): bool {
                return is_array($input);
            },
            'input' => [],
            'actual' => false,
            'expect' => [
                'supports' => true,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => function ($input): bool {
                return is_array($input);
            },
            'input' => null,
            'actual' => false,
            'expect' => [
                'supports' => false,
            ],
        ];
    }

    /**
     * @dataProvider provDummyResultFactory
     *
     * @param bool|\Closure $supports
     * @param mixed $input
     *
     * @psalm-param bool|\Closure(mixed):bool $supports
     * @psalm-param array{supports: mixed} $expect
     */
    public function testDummyResultFactory($supports, $input, bool $actual, array $expect): void
    {
        $dummyResultFactory = new DummyResultFactory($supports);

        $this->assertSame($expect['supports'], $dummyResultFactory->supports($input));

        $result = $dummyResultFactory->getResult($actual, $input);

        $this->assertInstanceOf(DummyResult::class, $result);
        $this->assertSame($actual, $result->actual());
    }
}
