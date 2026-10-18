<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ValueSelector;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs           = list{
 *                                pure-Closure(mixed):bool,
 *                                Closure(mixed,mixed,mixed):bool,
 *                                string,
 *                                string
 *                                }
 * @psalm-type SupportsTestCall   = array{args: list{mixed}, return: mixed}
 * @psalm-type SelectTestCall     = array{args: list{mixed,array-key}, return?: bool, retval?: mixed, exception?: string}
 * @psalm-type SubjectTestCall    = array{return: mixed}
 * @psalm-type SelectableTestCall = array{return: mixed}
 */
#[CoversClass(AbstractValueSelector::class)]
#[CoversClass(AbstractValueSelectorTest::class)]
#[Small]
final class AbstractValueSelectorTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsValueSelectorInterface(): void
    {
        $object = $this->createStub(AbstractValueSelector::class);
        self::assertInstanceOf(ValueSelectorInterface::class, $object);
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      supports?: ?SupportsTestCall,
     *      select?: ?SelectTestCall,
     *      subject?: ?SubjectTestCall,
     *      selectable?: ?SelectableTestCall
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provAbstractValueSelector(): iterable
    {
        $supportsArray = (
            /**
             * @param mixed $subject
             *
             * @psalm-param mixed $subject
             */
            fn (mixed $subject): bool => is_array($subject)
        );

        $selectFromArray =
        /**
         * @param mixed $subject
         * @param mixed $key
         * @param mixed $retval
         *
         * @psalm-param array     $subject
         * @psalm-param array-key $key
         * @psalm-param mixed     $retval
         */
        function (mixed $subject, mixed $key, mixed &$retval): bool {
            if (!array_key_exists($key, $subject)) {
                return false;
            }

            /** @psalm-var mixed */
            $retval = $subject[$key];

            return true;
        };

        //
        // 01
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs'   => [$supportsArray, $selectFromArray, 'an array', 'value'],
            'supports'   => ['args' => [['foo' => 'FOO']], 'return' => true],
            'select'     => ['args' => [['foo' => 'FOO'], 'bar'], 'return' => false, 'retval' => null],
            'subject'    => ['return' => 'an array'],
            'selectable' => ['return' => 'value'],
        ];

        //
        // 02
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs'   => [$supportsArray, $selectFromArray, 'an array', 'value'],
            'supports'   => ['args' => [['foo' => 'FOO']], 'return' => true],
            'select'     => ['args' => [['foo' => 'FOO'], 'foo'], 'return' => true, 'retval' => 'FOO'],
            'subject'    => ['return' => 'an array'],
            'selectable' => ['return' => 'value'],
        ];

        //
        // 03
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs'   => [$supportsArray, $selectFromArray, 'an array', 'value'],
            'supports'   => ['args' => [null], 'return' => false],
            'select'     => ['args' => [null, 'foo'], 'exception' => 'must be an array, NULL given'],
            'subject'    => ['return' => 'an array'],
            'selectable' => ['return' => 'value'],
        ];

        //
        // 04
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs'   => [$supportsArray, $selectFromArray, 'an array', 'value'],
            'supports'   => ['args' => ['lorem ipsum'], 'return' => false],
            'select'     => ['args' => ['lorem ipsum', 'foo'], 'exception' => 'must be an array, string given'],
            'subject'    => ['return' => 'an array'],
            'selectable' => ['return' => 'value'],
        ];

        //
        // 05
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs'   => [$supportsArray, $selectFromArray, 'an array', 'value'],
            'supports'   => ['args' => [new \stdClass()], 'return' => false],
            'select'     => ['args' => [new \stdClass(), 'foo'], 'exception' => 'must be an array, an object stdClass given'],
            'subject'    => ['return' => 'an array'],
            'selectable' => ['return' => 'value'],
        ];
    }

    /**
     * @psalm-param CtorArgs            $ctorArgs
     * @psalm-param ?SupportsTestCall   $supports
     * @psalm-param ?SelectTestCall     $select
     * @psalm-param ?SubjectTestCall    $subject
     * @psalm-param ?SelectableTestCall $selectable
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provAbstractValueSelector')]
    public function testAbstractValueSelector(
        array $ctorArgs,
        ?array $supports = null,
        ?array $select = null,
        ?array $subject = null,
        ?array $selectable = null
    ): void {
        $selector = self::getObject($ctorArgs);

        if (null !== $subject) {
            self::assertSame($subject['return'], $selector->subject());
        }

        if (null !== $selectable) {
            self::assertSame($selectable['return'], $selector->selectable());
        }

        if (null !== $supports) {
            self::assertSame($supports['return'], $selector->supports(...$supports['args']));
        }

        if (null !== $select) {
            $retval = null;
            $args = array_merge($select['args'], [&$retval]);
            if (array_key_exists('return', $select)) {
                self::assertSame($select['return'], $selector->select(...$args));
                if (array_key_exists('retval', $select)) {
                    self::assertSame($select['retval'], $retval);
                }
            }

            if (array_key_exists('exception', $select)) {
                self::expectException(InvalidArgumentException::class);
                self::expectExceptionMessage($select['exception']);

                $selector->select(...$args);
            }
        }
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     */
    public static function getObject(array $ctorArgs): AbstractValueSelector
    {
        return new
            /**
             * @psalm-template SubjectType
             *
             * @template-extends AbstractValueSelector<SubjectType>
             */
            class(...$ctorArgs) extends AbstractValueSelector {
                /**
                 * @psalm-param pure-Closure(mixed):bool        $supports
                 * @psalm-param Closure(mixed,mixed,mixed):bool $select
                 */
                public function __construct(
                    private readonly \Closure $supports,
                    private readonly \Closure $select,
                    private readonly string $subject,
                    private readonly string $selectable
                ) {}

                public function supports(mixed $input): bool
                {
                    return call_user_func($this->supports, $input);
                }

                /**
                 * @psalm-param array-key   $key
                 * @psalm-param SubjectType $subject
                 */
                protected function selectFromSupported(mixed $subject, mixed $key, mixed &$retval = null): bool
                {
                    return call_user_func_array($this->select, [$subject, $key, &$retval]);
                }

                /**
                 * @psalm-mutation-free
                 */
                public function subject(): string
                {
                    return $this->subject;
                }

                /**
                 * @psalm-mutation-free
                 */
                public function selectable(): string
                {
                    return $this->selectable;
                }
            };
    }
}
// vim: syntax=php sw=4 ts=4 et:
