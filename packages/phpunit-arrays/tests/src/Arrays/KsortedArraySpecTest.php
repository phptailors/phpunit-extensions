<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\AbstractArrayExpectations;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Comparator\DummyComparator;
use Tailors\PHPUnit\Predicate\ComparatorAwareComplementInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\KsortedArraySpec
 * @covers \Tailors\PHPUnit\Arrays\KsortedArraySpecTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs = list{0: ArrayLike, 1?: int}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class KsortedArraySpecTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractArraySpec(): void
    {
        $this->assertInstanceOf(AbstractArrayExpectations::class, new KsortedArraySpec([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryWrapperInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryWrapperInterface::class, new KsortedArraySpec([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsComparatorAwareComplementInterface(): void
    {
        $this->assertInstanceOf(ComparatorAwareComplementInterface::class, new KsortedArraySpec([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      factoryInput: ArrayLike,
     *      ksortedInput: mixed,
     * }>
     */
    public static function provKsortedArraySpec(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs'     => [['a' => 'A']],
            'factoryInput' => ['b' => 'X', 'a' => 'Y'],
            'ksortedInput' => ['a' => 'Y', 'b' => 'X'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs'     => [new \ArrayObject(['a' => 'A'])],
            'factoryInput' => new \ArrayObject(['b' => 'X', 'a' => 'Y']),
            'ksortedInput' => ['a' => 'Y', 'b' => 'X'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs'     => [['a' => 'A']],
            'factoryInput' => new \ArrayObject(['b' => 'X', 'a' => 'Y']),
            'ksortedInput' => ['a' => 'Y', 'b' => 'X'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs'     => [new \ArrayObject(['a' => 'A'])],
            'factoryInput' => ['b' => 'X', 'a' => 'Y'],
            'ksortedInput' => ['a' => 'Y', 'b' => 'X'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs'     => [['a' => 'A'], SORT_NUMERIC],
            'factoryInput' => ['b' => 'X', 'a' => 'Y', 2 => 'U', 0 => 'V'],
            'ksortedInput' => ['b' => 'X', 'a' => 'Y', 0 => 'V', 2 => 'U'],
        ];
    }

    /**
     * @dataProvider provKsortedArraySpec
     *
     * @param mixed $ksortedInput
     *
     * @psalm-param CtorArgs  $ctorArgs
     * @psalm-param ArrayLike $factoryInput
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testKsortedArraySpec(array $ctorArgs, iterable $factoryInput, $ksortedInput): void
    {
        $arraySpec = new KsortedArraySpec(...$ctorArgs);
        $resultFactory = $arraySpec->getResultFactory();

        $array = is_array($ctorArgs[0]) ? $ctorArgs[0] : iterator_to_array($ctorArgs[0]);

        $this->assertInstanceOf(KsortedArrayFactory::class, $resultFactory);
        $this->assertSame($array, iterator_to_array($arraySpec));

        $this->assertSame($ksortedInput, (array) $resultFactory->getResult(false, $factoryInput));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testComplement(): void
    {
        $arraySpec = new KsortedArraySpec([]);
        $comparator = new DummyComparator(false, 'similar to');

        $this->assertSame('an array similar to the specified one when ksorted', $arraySpec->complement($comparator));
    }
}
// vim: syntax=php sw=4 ts=4 et:
