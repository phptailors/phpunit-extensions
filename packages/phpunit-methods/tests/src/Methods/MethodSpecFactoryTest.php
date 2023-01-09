<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Methods;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Methods\MethodSpecFactory
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class MethodSpecFactoryTest extends TestCase
{
    public const IS_STATIC = MethodSpec::IS_STATIC;
    public const IS_PUBLIC = MethodSpec::IS_PUBLIC;
    public const IS_PROTECTED = MethodSpec::IS_PROTECTED;
    public const IS_PRIVATE = MethodSpec::IS_PRIVATE;
    public const IS_ABSTRACT = MethodSpec::IS_ABSTRACT;
    public const IS_FINAL = MethodSpec::IS_FINAL;

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
     * @psalm-return iterable<array-key,array{
     *  0: array{0:string},
     *  1: array{name:mixed, static:mixed, access:mixed, abstract:mixed, final:mixed}
     * }>
     */
    public function provFromString(): iterable
    {
        function results(array $array = [], string $name = 'foo')
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
     * @dataProvider provFromString
     */
    public function testFromString(string $string, array $expect): void
    {
        $factory = new MethodSpecFactory();
        $spec = $factory->fromString($string);
        $this->assertSame($expect['name'], $spec->getName());
        $this->assertSame($expect['static'], $spec->getStatic());
        $this->assertSame($expect['access'], $spec->getAccess());
        $this->assertSame($expect['abstract'], $spec->getAbstract());
        $this->assertSame($expect['final'], $spec->getFinal());
    }

    /**
     * @psalm-return array<array-key, array{0: string, 1:string}>
     */
    public function provFromStringSyntaxError(): iterable
    {
        yield ['', ''];

        yield ['ab^$&#', 'ab^$&#'];

        yield ['0foo', '0foo'];

        yield ['function ab^$&#', '^$&#'];

        yield ['function ab^$&#', '^$&#'];

        yield ['function foo ?', ' ?'];

        yield ['public function 123', '123'];

        yield ['public public function foo', 'public function foo'];

        yield ['public !public function foo', '!public function foo'];

        yield ['public protected function foo', 'protected function foo'];

        yield ['public !protected function foo', '!protected function foo'];

        yield ['public private function foo', 'private function foo'];

        yield ['public !private function foo', '!private function foo'];

        yield ['!public public function foo', 'public function foo'];

        yield ['!public !public function foo', '!public function foo'];

        yield ['!public protected function foo', 'protected function foo'];

        yield ['!public !protected function foo', '!protected function foo'];

        yield ['!public private function foo', 'private function foo'];

        yield ['!public !private function foo', '!private function foo'];

        yield ['protected public function foo', 'public function foo'];

        yield ['protected !public function foo', '!public function foo'];

        yield ['protected protected function foo', 'protected function foo'];

        yield ['protected !protected function foo', '!protected function foo'];

        yield ['protected private function foo', 'private function foo'];

        yield ['protected !private function foo', '!private function foo'];

        yield ['!protected public function foo', 'public function foo'];

        yield ['!protected !public function foo', '!public function foo'];

        yield ['!protected protected function foo', 'protected function foo'];

        yield ['!protected !protected function foo', '!protected function foo'];

        yield ['!protected private function foo', 'private function foo'];

        yield ['!protected !private function foo', '!private function foo'];

        yield ['private public function foo', 'public function foo'];

        yield ['private !public function foo', '!public function foo'];

        yield ['private protected function foo', 'protected function foo'];

        yield ['private !protected function foo', '!protected function foo'];

        yield ['private private function foo', 'private function foo'];

        yield ['private !private function foo', '!private function foo'];

        yield ['!private public function foo', 'public function foo'];

        yield ['!private !public function foo', '!public function foo'];

        yield ['!private protected function foo', 'protected function foo'];

        yield ['!private !protected function foo', '!protected function foo'];

        yield ['!private private function foo', 'private function foo'];

        yield ['!private !private function foo', '!private function foo'];

        yield ['abstract abstract function foo', 'abstract function foo'];

        yield ['abstract !abstract function foo', '!abstract function foo'];

        yield ['abstract final function foo', 'final function foo'];

        yield ['abstract !final function foo', '!final function foo'];

        yield ['!abstract abstract function foo', 'abstract function foo'];

        yield ['!abstract !abstract function foo', '!abstract function foo'];

        yield ['!abstract final function foo', 'final function foo'];

        yield ['final final function foo', 'final function foo'];

        yield ['final !final function foo', '!final function foo'];

        yield ['final abstract function foo', 'abstract function foo'];

        yield ['final !abstract function foo', '!abstract function foo'];

        yield ['!final final function foo', 'final function foo'];

        yield ['!final !final function foo', '!final function foo'];

        yield ['!final abstract function foo', 'abstract function foo'];

        yield ['static static function foo', 'static function foo'];

        yield ['static !static function foo', '!static function foo'];

        yield ['abstract static final function foo', 'final function foo'];
    }

    /**
     * @dataProvider provFromStringSyntaxError
     */
    public function testFromStringSyntaxError(string $string, string $at): void
    {
        $factory = new MethodSpecFactory();

        $this->expectException(MethodSpecSyntaxError::class);
        $this->expectExceptionMessage(sprintf('syntax error at "%s"', $at));

        $factory->fromString($string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
