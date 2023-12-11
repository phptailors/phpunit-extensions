<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\ComparatorWrapperInterface;
use Tailors\PHPUnit\Comparator\EqualityComparator;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\RecursiveComparatorValidator
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveComparatorValidatorTest extends TestCase
{
    public static function createSelectionMock(TestCase $test, array $array): MockObject
    {
        $selection = $test->createMock(SelectionInterface::class);
        $selection->expects($test->any())
            ->method('getArrayCopy')
            ->willReturn($array)
        ;

        return $selection;
    }

    public static function createComparatorWrapperMock(TestCase $test, ComparatorInterface $comparator): MockObject
    {
        $wrapper = $test->createMock(ComparatorWrapperInterface::class);
        $wrapper->expects($test->any())
            ->method('getComparator')
            ->willReturn($comparator)
        ;

        return $wrapper;
    }

    public static function createSelectionWrapperMock(TestCase $test, $selection = null): MockObject
    {
        $wrapper = $test->createMock(SelectionWrapperInterface::class);
        self::setSelectionWrapperMockSelection($test, $wrapper, $selection);

        return $wrapper;
    }

    public static function setSelectionWrapperMockSelection(TestCase $test, MockObject $wrapper, $selection = null): void
    {
        if (is_array($selection)) {
            $selection = self::createSelectionMock($test, $selection);
        }

        if (null !== $selection) {
            $wrapper->expects($test->any())
                ->method('getSelection')
                ->willReturn($selection)
            ;
        }
    }

    public function createConstraint(ComparatorInterface $comparator, $selection = []): ConstraintInterface
    {
        $wrapper = $this->createMock(ConstraintInterface::class);
        if (is_array($selection)) {
            $selection = $this->createSelectionMock($selection);
        }

        $wrapper->expects($this->any())
            ->method('getComparator')
            ->willReturn($comparator)
        ;

        $wrapper->expects($this->any())
            ->method('getSelection')
            ->willReturn($selection)
        ;

        return $wrapper;
    }

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

        $equalityWrapper = fn (TestCase $test) => self::createComparatorWrapperMock($test, new EqualityComparator());
        $identityWrapper = fn (TestCase $test) => self::createComparatorWrapperMock($test, new IdentityComparator());
        $emptySelection = fn (TestCase $test) => $test->createMock(SelectionInterface::class);

        $circularWrapper = function (TestCase $test) use ($equalityWrapper, $identityWrapper) {
            $circularWrapper = self::createSelectionWrapperMock($test);

            $circularArray = [
                'circular' => $circularWrapper,
                'equality' => $equalityWrapper($test),
                'identity' => $identityWrapper($test),
            ];

            self::setSelectionWrapperMockSelection($test, $circularWrapper, $circularArray);

            return $circularWrapper;
        };

        return [
            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => fn (TestCase $test) => [
                    [],
                    1,
                ],
                'expect' => [
                ],
            ],

            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => fn (TestCase $test) => [
                    [
                        'foo' => 'FOO',
                        'bar' => [
                            'gez' => $equalityComparator,
                            'qux' => 12,
                        ],
                    ],
                    1,
                ],
                'expect' => [
                ],
            ],

            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => fn (TestCase $test) => [
                    [
                        'foo' => $identityWrapper($test),
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
                'args'       => fn (TestCase $test) => [
                    [
                        'err' => $equalityWrapper($test),
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
                'args'       => fn (TestCase $test) => [
                    [
                        'err1' => $identityWrapper($test),
                        'bar'  => [
                            'gez'  => 'GEZ',
                            'err2' => $identityWrapper($test),
                        ],
                        'frd' => $equalityWrapper($test),
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
                'args'       => fn (TestCase $test) => [
                    [
                        'foo' => self::createSelectionWrapperMock($test, [
                            'bar'  => 'BAR',
                            'err1' => $identityWrapper($test),
                            'qux'  => self::createSelectionWrapperMock($test, [
                                'err2' => $identityWrapper($test),
                            ]),
                        ]),
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
                'args'       => fn (TestCase $test) => [
                    [
                        'circular' => $circularWrapper($test),
                        'identity' => $identityWrapper($test),
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
     * @param \Closure(TestCase):array $args
     *
     * @dataProvider provValidate
     */
    public function testValidate(ComparatorInterface $comparator, \Closure $args, array $expect = []): void
    {
        $validator = new RecursiveComparatorValidator($comparator);

        if (array_key_exists('exception', $expect)) {
            $this->expectException($expect['exception']);
            $this->expectExceptionMessage($expect['message']);
        }

        $this->assertNull($validator->validate(...$args($this)));
    }
}
// vim: syntax=php sw=4 ts=4 et:
