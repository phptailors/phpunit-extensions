<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Methods;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Polyfill\ExpectExceptionMessageIsOrContainsTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type FromStringExpectArray = array{
 *                                   name: mixed,
 *                                   static: mixed,
 *                                   access: mixed,
 *                                   abstract: mixed,
 *                                   final: mixed,
 *                                   ...
 *                                   }
 */
#[CoversClass(MethodSpecFactory::class)]
#[CoversClass(MethodSpecFactoryTest::class)]
#[Small]
final class MethodSpecFactoryTest extends TestCase
{
    use ExpectExceptionMessageIsOrContainsTrait;

    public const int IS_STATIC = MethodSpec::IS_STATIC;
    public const int IS_PUBLIC = MethodSpec::IS_PUBLIC;
    public const int IS_PROTECTED = MethodSpec::IS_PROTECTED;
    public const int IS_PRIVATE = MethodSpec::IS_PRIVATE;
    public const int IS_ABSTRACT = MethodSpec::IS_ABSTRACT;
    public const int IS_FINAL = MethodSpec::IS_FINAL;

    public const MMASK =
        self::IS_STATIC
        | self::IS_PUBLIC
        | self::IS_PROTECTED
        | self::IS_PRIVATE
        | self::IS_ABSTRACT
        | self::IS_FINAL;

    public const VMASK =
        self::IS_PUBLIC
        | self::IS_PROTECTED
        | self::IS_PRIVATE;

    public const NOT_PUBLIC = self::VMASK & ~self::IS_PUBLIC;
    public const NOT_PROTECTED = self::VMASK & ~self::IS_PROTECTED;
    public const NOT_PRIVATE = self::VMASK & ~self::IS_PRIVATE;

    /**
     * @psalm-return iterable<array-key, list{string, FromStringExpectArray}>
     *
     * @codeCoverageIgnore
     */
    public static function provFromString(): iterable
    {
        /** @psalm-return FromStringExpectArray */
        function results(array $array = [], string $name = 'foo'): array
        {
            $defaults = [
                'name'     => $name,
                'static'   => null,
                'access'   => null,
                'abstract' => null,
                'final'    => null,
            ];

            return array_merge($defaults, $array);
        }

        yield ['_', results([], '_')];

        yield ['__', results([], '__')];

        yield ['_1', results([], '_1')];

        yield ['test123', results([], 'test123')];

        yield ['Cam3lCase', results([], 'Cam3lCase')];

        /** @psalm-suppress UndefinedConstant */
        $accessCases = [
            [[], []],
            [['public'], ['access' => self::IS_PUBLIC]],
            [['protected'], ['access' => self::IS_PROTECTED]],
            [['private'], ['access' => self::IS_PRIVATE]],
            [['!public'], ['access' => self::NOT_PUBLIC]],
            [['!protected'], ['access' => self::NOT_PROTECTED]],
            [['!private'], ['access' => self::NOT_PRIVATE]],
        ];

        $staticCases = [
            [[], []],
            [['static'], ['static' => true]],
            [['!static'], ['static' => false]],
        ];

        $absfinCases = [
            [[], []],
            [['abstract'], ['abstract' => true]],
            [['!abstract'], ['abstract' => false]],
            [['final'], ['final' => true]],
            [['!final'], ['final' => false]],
            [['!final !abstract'], ['final' => false, 'abstract' => false]],
            [['!abstract !final'], ['final' => false, 'abstract' => false]],
        ];

        foreach ($accessCases as $a) {
            foreach ($staticCases as $b) {
                foreach ($absfinCases as $c) {
                    $inputs = [
                        array_merge($a[0], $b[0], $c[0]),
                        array_merge($a[0], $c[0], $b[0]),

                        array_merge($b[0], $a[0], $c[0]),
                        array_merge($b[0], $c[0], $a[0]),

                        array_merge($c[0], $a[0], $b[0]),
                        array_merge($c[0], $b[0], $a[0]),
                    ];
                    $result = array_merge($a[1], $b[1], $c[1]);
                    foreach ($inputs as $input) {
                        $inputStr = implode(' ', array_merge($input, ['function', 'foo']));

                        yield [$inputStr, results($result)];
                    }
                }
            }
        }

        yield [
            '!abstract public !final function foo',
            results([
                'abstract' => false,
                'final'    => false,
                'access'   => self::IS_PUBLIC,
            ]),
        ];
    }

    /**
     * @psalm-param FromStringExpectArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provFromString')]
    public function testFromString(string $string, array $expect): void
    {
        $factory = new MethodSpecFactory();
        $spec = $factory->fromString($string);
        self::assertSame($expect['name'], $spec->getName());
        self::assertSame($expect['static'], $spec->getStatic());
        self::assertSame($expect['access'], $spec->getAccess());
        self::assertSame($expect['abstract'], $spec->getAbstract());
        self::assertSame($expect['final'], $spec->getFinal());
    }

    /**
     * @psalm-return iterable<string, list{string, string}>
     *
     * @codeCoverageIgnore
     */
    public static function provFromStringSyntaxError(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => ['', ''];

        yield basename(__FILE__).':'.__LINE__ => ['ab^$&#', 'ab^$&#'];

        yield basename(__FILE__).':'.__LINE__ => ['0foo', '0foo'];

        yield basename(__FILE__).':'.__LINE__ => ['function ab^$&#', '^$&#'];

        yield basename(__FILE__).':'.__LINE__ => ['function ab^$&#', '^$&#'];

        yield basename(__FILE__).':'.__LINE__ => ['function foo ?', ' ?'];

        yield basename(__FILE__).':'.__LINE__ => ['public function 123', '123'];

        yield basename(__FILE__).':'.__LINE__ => ['public public function foo', 'public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['public !public function foo', '!public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['public protected function foo', 'protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['public !protected function foo', '!protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['public private function foo', 'private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['public !private function foo', '!private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!public public function foo', 'public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!public !public function foo', '!public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!public protected function foo', 'protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!public !protected function foo', '!protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!public private function foo', 'private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!public !private function foo', '!private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['protected public function foo', 'public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['protected !public function foo', '!public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['protected protected function foo', 'protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['protected !protected function foo', '!protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['protected private function foo', 'private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['protected !private function foo', '!private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!protected public function foo', 'public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!protected !public function foo', '!public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!protected protected function foo', 'protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!protected !protected function foo', '!protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!protected private function foo', 'private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!protected !private function foo', '!private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['private public function foo', 'public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['private !public function foo', '!public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['private protected function foo', 'protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['private !protected function foo', '!protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['private private function foo', 'private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['private !private function foo', '!private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!private public function foo', 'public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!private !public function foo', '!public function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!private protected function foo', 'protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!private !protected function foo', '!protected function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!private private function foo', 'private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!private !private function foo', '!private function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['abstract abstract function foo', 'abstract function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['abstract !abstract function foo', '!abstract function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['abstract final function foo', 'final function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['abstract !final function foo', '!final function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!abstract abstract function foo', 'abstract function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!abstract !abstract function foo', '!abstract function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!abstract final function foo', 'final function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['final final function foo', 'final function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['final !final function foo', '!final function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['final abstract function foo', 'abstract function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['final !abstract function foo', '!abstract function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!final final function foo', 'final function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!final !final function foo', '!final function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['!final abstract function foo', 'abstract function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['static static function foo', 'static function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['static !static function foo', '!static function foo'];

        yield basename(__FILE__).':'.__LINE__ => ['abstract static final function foo', 'final function foo'];
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provFromStringSyntaxError')]
    public function testFromStringSyntaxError(string $string, string $at): void
    {
        $factory = new MethodSpecFactory();

        self::expectException(MethodSpecSyntaxError::class);
        self::expectExceptionMessageIsOrContains(sprintf('syntax error at "%s"', $at));

        $factory->fromString($string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
