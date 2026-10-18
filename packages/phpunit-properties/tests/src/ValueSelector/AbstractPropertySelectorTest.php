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
use Tailors\PHPUnit\Polyfill\ExpectExceptionMessageIsOrContainsTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs     = list{string,Closure(mixed):bool,Closure(mixed,array-key):mixed}
 * @psalm-type CallSupports = array{input: mixed, return: mixed}
 * @psalm-type CallSelect   = array{subject: mixed, key: array-key, retval?: mixed, return?: mixed, exception?: string}
 */
#[CoversClass(AbstractPropertySelector::class)]
#[CoversClass(AbstractPropertySelectorTest::class)]
#[Small]
final class AbstractPropertySelectorTest extends TestCase
{
    use ExpectExceptionMessageIsOrContainsTrait;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     */
    public static string $staticFoo = 'static foo';

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function staticFoo(): string
    {
        return 'static foo()';
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractValueSelector(): void
    {
        $selector = $this->createStub(AbstractPropertySelector::class);
        self::assertInstanceOf(AbstractValueSelector::class, $selector);
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      supports?: CallSupports,
     *      select?: CallSelect
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provAbstractPropertySelector(): iterable
    {
        $classPropertySelector = [
            'a class',

            /**
             * @param mixed $input
             *
             * @psalm-assert-if-true class-string $input
             */
            fn (mixed $input): bool => is_string($input) && class_exists($input),

            /**
             * @param mixed $subject
             * @param mixed $key
             *
             * @return mixed
             *
             * @psalm-param class-string $subject
             * @psalm-param array-key    $key
             *
             * @psalm-suppress UnusedClosureParam
             */
            fn (mixed $subject, mixed $key): mixed => $subject::${$key},
        ];

        $objectPropertySelector = [
            'an object',

            /**
             * @param mixed $input
             *
             * @psalm-assert-if-true class-string $input
             */
            fn (mixed $input): bool => is_object($input),

            /**
             * @param mixed $subject
             * @param mixed $key
             *
             * @return mixed
             *
             * @psalm-param class-string $subject
             * @psalm-param array-key    $key
             */
            fn (mixed $subject, mixed $key): mixed => $subject->{$key},
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => $classPropertySelector,
            'supports' => [
                'input'  => 'lorem ipsum',
                'return' => false,
            ],
            'select'   => [
                'subject'   => 'lorem ipsum',
                'key'       => 'unimportant',
                'exception' => 'must be a class, string given',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => $classPropertySelector,
            'supports' => [
                'input'  => self::class,
                'return' => true,
            ],
            'select'   => [
                'subject' => self::class,
                'key'     => 'inexistentProperty',
                'retval'  => null,
                'return'  => false,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => $classPropertySelector,
            'supports' => [
                'input'  => self::class,
                'return' => true,
            ],
            'select'   => [
                'subject' => self::class,
                'key'     => 'inexistentMethod()',
                'retval'  => null,
                'return'  => false,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => $classPropertySelector,
            'supports' => [
                'input'  => self::class,
                'return' => true,
            ],
            'select'   => [
                'subject' => self::class,
                'key'     => 'staticFoo',
                'retval'  => 'static foo',
                'return'  => true,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => $classPropertySelector,
            'supports' => [
                'input'  => self::class,
                'return' => true,
            ],
            'select'   => [
                'subject' => self::class,
                'key'     => 'staticFoo()',
                'retval'  => 'static foo()',
                'return'  => true,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => $objectPropertySelector,
            'supports' => [
                'input'  => 'lorem ipsum',
                'return' => false,
            ],
            'select'   => [
                'subject'   => 'lorem ipsum',
                'key'       => 'unimportant',
                'exception' => 'must be an object, string given',
            ],
        ];

        $exception = new \Exception('lorem ipsum');

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => $objectPropertySelector,
            'supports' => [
                'input'  => $exception,
                'return' => true,
            ],
            'select'   => [
                'subject' => $exception,
                'key'     => 'inexistentProperty',
                'retval'  => null,
                'return'  => false,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => $objectPropertySelector,
            'supports' => [
                'input'  => $exception,
                'return' => true,
            ],
            'select'   => [
                'subject' => $exception,
                'key'     => 'inexistentMethod()',
                'retval'  => null,
                'return'  => false,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => $objectPropertySelector,
            'supports' => [
                'input'  => $exception,
                'return' => true,
            ],
            'select'   => [
                'subject' => $exception,
                'key'     => 'getMessage()',
                'retval'  => 'lorem ipsum',
                'return'  => true,
            ],
        ];
    }

    /**
     * @psalm-param CtorArgs     $ctorArgs
     * @psalm-param CallSupports $supports
     * @psalm-param CallSelect   $select
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provAbstractPropertySelector')]
    public function testAbstractPropertySelector(array $ctorArgs, ?array $supports = null, ?array $select = null): void
    {
        $selector = self::getObject($ctorArgs);

        self::assertSame('properties', $selector->selectable());

        if (null !== $supports) {
            self::assertSame($supports['return'], $selector->supports($supports['input']));
        }

        if (null !== $select) {
            if (null !== ($exception = $select['exception'] ?? null)) {
                $this->expectException(InvalidArgumentException::class);
                $this->expectExceptionMessageIsOrContains($exception);
            }

            $return = $selector->select($select['subject'], $select['key'], $retval);

            if (array_key_exists('return', $select)) {
                self::assertSame($select['return'], $return);
            }

            if (array_key_exists('retval', $select)) {
                self::assertSame($select['retval'], $retval);
            }
        }
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     */
    public static function getObject(array $ctorArgs): AbstractPropertySelector
    {
        return new
            /**
             * @psalm-template SubjectType of object|class-string
             *
             * @template-extends AbstractPropertySelector<SubjectType>
             */
            class(...$ctorArgs) extends AbstractPropertySelector {
                /**
                 * @psalm-param \Closure(mixed):bool                  $supports
                 * @psalm-param \Closure(SubjectType,array-key):mixed $getSubjectAttribute
                 */
                public function __construct(
                    private readonly string $subject,
                    private readonly \Closure $supports,
                    private readonly \Closure $getSubjectAttribute
                ) {}

                /**
                 * @psalm-assert-if-true SubjectType $input
                 */
                public function supports(mixed $input): bool
                {
                    return call_user_func($this->supports, $input);
                }

                public function subject(): string
                {
                    return $this->subject;
                }

                /**
                 * @psalm-param SubjectType $subject
                 * @psalm-param array-key   $key
                 */
                protected function getSubjectAttribute(mixed $subject, mixed $key): mixed
                {
                    return call_user_func($this->getSubjectAttribute, $subject, $key);
                }
            };
    }
}
// vim: syntax=php sw=4 ts=4 et:
