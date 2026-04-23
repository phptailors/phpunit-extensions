<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\DummyComparatorWrapper;
use Tailors\PHPUnit\Comparator\EqualityComparator;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(RecursiveComparatorValidator::class)]
final class RecursiveComparatorValidatorTest extends TestCase
{
    public static function makeFailureMessage(
        int $argument,
        string $function,
        string $comparatorClass,
        int $failures
    ): string {
        return sprintf(
            'Argument %d passed to %s() must be an array with only %s '.
            'nested comparators, an array with %d comparator%s of other type given',
            $argument,
            $function,
            $comparatorClass,
            $failures,
            1 === $failures ? '' : 's'
        );
    }

    //
    //
    // TESTS
    //
    //

    public static function provValidate(): array
    {
        $equalityComparator = new EqualityComparator();
        $identityComparator = new IdentityComparator();

        $equalityWrapper = new DummyComparatorWrapper(new EqualityComparator());
        $identityWrapper = new DummyComparatorWrapper(new IdentityComparator());

        $emptyValues = new DummyValues(true);
        $dummyValues = new DummyValues(true);

        $circularWrapper = new DummyValuesWrapper($dummyValues);

        $circularArray = [
            'circular' => $circularWrapper,
            'equality' => $equalityWrapper,
            'identity' => $identityWrapper,
        ];
        $dummyValues->exchangeArray($circularArray);

        return [
            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => [[], 1],
                'expect'     => [],
            ],

            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => [
                    [
                        'foo' => 'FOO',
                        'bar' => [
                            'gez' => $equalityComparator,
                            'qux' => 12,
                        ],
                    ],
                    1,
                ],
                'expect' => [],
            ],

            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => [
                    [
                        'foo' => $identityWrapper,
                    ],
                    123,
                ],
                'expect' => [
                    'exception' => InvalidArgumentException::class,
                    'message'   => self::makeFailureMessage(123, __CLASS__.'::testValidate', 'EqualityComparator', 1),
                ],
            ],

            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $identityComparator,
                'args'       => [
                    [
                        'err' => $equalityWrapper,
                    ],
                    123,
                ],
                'expect' => [
                    'exception' => InvalidArgumentException::class,
                    'message'   => self::makeFailureMessage(123, __CLASS__.'::testValidate', 'IdentityComparator', 1),
                ],
            ],

            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => [
                    [
                        'err1' => $identityWrapper,
                        'bar'  => [
                            'gez'  => 'GEZ',
                            'err2' => $identityWrapper,
                        ],
                        'frd' => $equalityWrapper,
                    ],
                    11,
                ],
                'expect' => [
                    'exception' => InvalidArgumentException::class,
                    'message'   => self::makeFailureMessage(11, __CLASS__.'::testValidate', 'EqualityComparator', 2),
                ],
            ],

            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => [
                    [
                        'foo' => new DummyValuesWrapper(new DummyValues(true, [
                            'bar'  => 'BAR',
                            'err1' => $identityWrapper,
                            'qux'  => new DummyValuesWrapper(new DummyValues(true, [
                                'err2' => $identityWrapper,
                            ])),
                        ])),
                    ],
                    123,
                ],
                'expect' => [
                    'exception' => InvalidArgumentException::class,
                    'message'   => self::makeFailureMessage(123, __CLASS__.'::testValidate', 'EqualityComparator', 2),
                ],
            ],

            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => [
                    [
                        'circular' => $circularWrapper,
                        'identity' => $identityWrapper,
                    ],
                    123,
                ],
                'expect' => [
                    'exception' => InvalidArgumentException::class,
                    'message'   => self::makeFailureMessage(123, __CLASS__.'::testValidate', 'EqualityComparator', 2),
                ],
            ],
        ];
    }

    /**
     * @param array $args
     */
    #[DataProvider('provValidate')]
    public function testValidate(ComparatorInterface $comparator, array $args, array $expect = []): void
    {
        $validator = new RecursiveComparatorValidator($comparator);

        if (array_key_exists('exception', $expect)) {
            $this->expectException($expect['exception']);
            $this->expectExceptionMessage($expect['message']);
        }

        $this->assertNull($validator->validate(...$args));
    }
}
// vim: syntax=php sw=4 ts=4 et:
