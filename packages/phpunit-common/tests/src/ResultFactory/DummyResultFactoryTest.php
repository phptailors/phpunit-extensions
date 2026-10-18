<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ResultFactory;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Result\DummyResult;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
#[CoversClass(DummyResultFactory::class)]
#[CoversClass(DummyResultFactoryTest::class)]
#[Small]
final class DummyResultFactoryTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryInterface(): void
    {
        self::assertInstanceOf(ResultFactoryInterface::class, new DummyResultFactory(false));
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
     *
     * @codeCoverageIgnore
     */
    public static function provDummyResultFactory(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => true,
            'input'    => null,
            'actual'   => false,
            'expect'   => [
                'supports' => true,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => false,
            'input'    => null,
            'actual'   => false,
            'expect'   => [
                'supports' => false,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => true,
            'input'    => null,
            'actual'   => true,
            'expect'   => [
                'supports' => true,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => false,
            'input'    => null,
            'actual'   => true,
            'expect'   => [
                'supports' => false,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => /** @psalm-param mixed $input */
            fn ($input): bool => is_array($input),
            'input'    => [],
            'actual'   => false,
            'expect'   => [
                'supports' => true,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'supports' => /** @psalm-param mixed $input */
            fn ($input): bool => is_array($input),
            'input'    => null,
            'actual'   => false,
            'expect'   => [
                'supports' => false,
            ],
        ];
    }

    /**
     * @param bool|\Closure $supports
     * @param mixed         $input
     *
     * @psalm-param bool|\Closure(mixed):bool $supports
     * @psalm-param array{supports: mixed}    $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provDummyResultFactory')]
    public function testDummyResultFactory($supports, $input, bool $actual, array $expect): void
    {
        $dummyResultFactory = new DummyResultFactory($supports);

        self::assertSame($expect['supports'], $dummyResultFactory->supports($input));

        $result = $dummyResultFactory->getResult($actual, $input);

        self::assertInstanceOf(DummyResult::class, $result);
        self::assertSame($actual, $result->actual());
    }
}
