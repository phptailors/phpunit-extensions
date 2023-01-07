<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
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
    public function createSelectionMock(array $array): MockObject
    {
        $selection = $this->createMock(SelectionInterface::class);
        $selection->expects($this->any())
            ->method('getArrayCopy')
            ->willReturn($array)
        ;

        return $selection;
    }

    public function createComparatorWrapperMock(ComparatorInterface $comparator): MockObject
    {
        $wrapper = $this->createMock(ComparatorWrapperInterface::class);
        $wrapper->expects($this->any())
            ->method('getComparator')
            ->willReturn($comparator)
        ;

        return $wrapper;
    }

    public function createSelectionWrapperMock($selection = null): MockObject
    {
        $wrapper = $this->createMock(SelectionWrapperInterface::class);
        $this->setSelectionWrapperMockSelection($wrapper, $selection);

        return $wrapper;
    }

    public function setSelectionWrapperMockSelection(MockObject $wrapper, $selection = null): void
    {
        if (is_array($selection)) {
            $selection = $this->createSelectionMock($selection);
        }

        if (null !== $selection) {
            $wrapper->expects($this->any())
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

    public function provValidate(): array
    {
        $equalityComparator = new EqualityComparator();
        $identityComparator = new IdentityComparator();
        $equalityWrapper = $this->createComparatorWrapperMock(new EqualityComparator());
        $identityWrapper = $this->createComparatorWrapperMock(new IdentityComparator());
        $emptySelection = $this->createMock(SelectionInterface::class);

        $circularWrapper = $this->createSelectionWrapperMock();
        $circularArray = [
            'circular' => $circularWrapper,
            'equality' => $equalityWrapper,
            'identity' => $identityWrapper,
        ];
        $this->setSelectionWrapperMockSelection($circularWrapper, $circularArray);

        return [
            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => [
                    [],
                    1,
                ],
                'expect'     => [
                ],
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
                'expect'     => [
                ],
            ],

            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => [
                    [
                        'foo' => $identityWrapper,
                    ],
                    123,
                ],
                'expect'     => [
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
                'expect'     => [
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
                        'frd'  => $equalityWrapper,
                    ],
                    11,
                ],
                'expect'     => [
                    'exception' => InvalidArgumentException::class,
                    'message'   => self::makeFailureMessage(11, __CLASS__.'::testValidate', 'EqualityComparator', 2),
                ],
            ],

            'RecursiveComparatorValidatorTest.php:'.__LINE__ => [
                'comparator' => $equalityComparator,
                'args'       => [
                    [
                        'foo' => $this->createSelectionWrapperMock([
                            'bar'  => 'BAR',
                            'err1' => $identityWrapper,
                            'qux'  => $this->createSelectionWrapperMock([
                                'err2' => $identityWrapper,
                            ]),
                        ]),
                    ],
                    123,
                ],
                'expect'     => [
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
                'expect'     => [
                    'exception' => InvalidArgumentException::class,
                    'message'   => self::makeFailureMessage(123, __CLASS__.'::testValidate', 'EqualityComparator', 2),
                ],
            ],
        ];
    }

    /**
     * @dataProvider provValidate
     */
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
