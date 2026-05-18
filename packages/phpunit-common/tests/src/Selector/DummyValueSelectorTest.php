<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Selector;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Selector\DummyValueSelector
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs           = list{
 *                                bool|\Closure(mixed):bool,
 *                                bool|\Closure(mixed,mixed,mixed):bool,
 *                                string,
 *                                string
 *                                }
 * @psalm-type SupportsTestCall   = array{args: list{mixed}, return: mixed}
 * @psalm-type SelectTestCall     = array{args: list{mixed,mixed}, return: bool, retval?: mixed}
 * @psalm-type SubjectTestCall    = array{return: mixed}
 * @psalm-type SelectableTestCall = array{return: mixed}
 *                                }
 */
final class DummyValueSelectorTest extends TestCase
{
    /**
     * @psalm-return iterable<string, array{
     *      ctor: CtorArgs,
     *      supports?: null|SupportsTestCall,
     *      select?: null|SelectTestCall
     *      subject?: null|SubjectTestCall,
     *      selectable?: null|SelectableTestCall
     * }>
     */
    public static function provDummyValueSelector(): iterable
    {
        $supports = (fn ($subject): bool => is_array($subject));

        $select = function ($subject, $key, &$retval): bool {
            if (!array_key_exists($key, $subject)) {
                return false;
            }
            $retval = $subject[$key];

            return true;
        };

        //
        // 01
        //

        yield 'DummyValueSelectorTest.php:'.__LINE__ => [
            'ctor'       => [],
            'supports'   => ['args' => [null], 'return' => false],
            'select'     => ['args' => [['foo' => 'FOO'], 'foo'], 'return' => false],
            'subject'    => ['return' => ''],
            'selectable' => ['return' => ''],
        ];

        //
        // 02
        //

        yield 'DummyValueSelectorTest.php:'.__LINE__ => [
            'ctor'     => [true],
            'supports' => ['args' => [null], 'return' => true],
            'select'   => ['args' => [null, 'foo'], 'return' => false],
        ];

        //
        // 03
        //

        yield 'DummyValueSelectorTest.php:'.__LINE__ => [
            'ctor'     => [false, true],
            'supports' => ['args' => [null], 'return' => false],
            'select'   => ['args' => [null, 'foo'], 'return' => true],
        ];

        //
        // 04
        //

        yield 'DummyValueSelectorTest.php:'.__LINE__ => [
            'ctor'     => [true, true],
            'supports' => ['args' => [null], 'return' => true],
            'select'   => ['args' => [null, 'foo'], 'return' => true],
        ];

        //
        // 05
        //

        yield 'DummyValueSelectorTest.php:'.__LINE__ => [
            'ctor'       => [false, false, 'SUBJECT', 'SELECTABLE'],
            'supports'   => null,
            'select'     => null,
            'subject'    => ['return' => 'SUBJECT'],
            'selectable' => ['return' => 'SELECTABLE'],
        ];

        //
        // 06
        //

        yield 'DummyValueSelectorTest.php:'.__LINE__ => [
            'ctor'       => [$supports, $select, 'array', 'value'],
            'supports'   => ['args' => [['foo' => 'FOO']], 'return' => true],
            'select'     => ['args' => [['foo' => 'FOO'], 'bar'], 'return' => false, 'retval' => null],
            'subject'    => ['return' => 'array'],
            'selectable' => ['return' => 'value'],
        ];

        //
        // 07
        //

        yield 'DummyValueSelectorTest.php:'.__LINE__ => [
            'ctor'       => [$supports, $select, 'array', 'value'],
            'supports'   => ['args' => [['foo' => 'FOO']], 'return' => true],
            'select'     => ['args' => [['foo' => 'FOO'], 'foo'], 'return' => true, 'retval' => 'FOO'],
            'subject'    => ['return' => 'array'],
            'selectable' => ['return' => 'value'],
        ];
    }

    /**
     * @dataProvider provDummyValueSelector
     *
     * @psalm-param CtorArgs            $ctor
     * @psalm-param ?SupportsTestCall   $support
     * @psalm-param ?SelectTestCall     $select
     * @psalm-param ?SubjectTestCall    $subject
     * @psalm-param ?SelectableTestCall $selectable
     */
    public function testDummyValueSelector(
        array $ctor,
        ?array $supports = null,
        ?array $select = null,
        ?array $subject = null,
        ?array $selectable = null
    ): void {
        $selector = new DummyValueSelector(...$ctor);

        if (null !== $supports) {
            $this->assertSame($supports['return'], $selector->supports(...$supports['args']));
        }

        if (null !== $select) {
            $retval = null;
            $args = array_merge($select['args'], [&$retval]);
            $this->assertSame($select['return'], $selector->select(...$args));
            if (array_key_exists('retval', $select)) {
                $this->assertSame($select['retval'], $retval);
            }
        }

        if (null !== $subject) {
            $this->assertSame($subject['return'], $selector->subject());
        }

        if (null !== $selectable) {
            $this->assertSame($selectable['return'], $selector->selectable());
        }
    }
}
// vim: syntax=php sw=4 ts=4 et:
