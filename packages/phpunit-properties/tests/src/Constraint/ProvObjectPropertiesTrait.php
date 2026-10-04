<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike            = iterable<array-key, mixed>
 * @psalm-type CreateConstraintArgs = list{ArrayLike}
 */
trait ProvObjectPropertiesTrait
{
    /**
     * @psalm-param CreateConstraintArgs $args
     */
    abstract public static function createConstraint(array $args): Constraint;

    // @codeCoverageIgnoreStart

    /**
     * @psalm-return iterable<string, array{
     *      expect: array<string, mixed>,
     *      actual: object,
     *      string: string
     * }>
     */
    public static function provObjectPropertiesIdenticalTo(): iterable
    {
        $esmith = new class() {
            /** @var string */
            public $name = 'Emily';

            /** @var string */
            public $last = 'Smith';

            /** @var int */
            public $age = 20;

            /** @var ?object */
            public $husband;

            /** @var object[] */
            public $family = [];

            /** @var int */
            private $salary = 98;

            public function getSalary(): int
            {
                return $this->salary;
            }

            public function getDebit(): int
            {
                return -$this->salary;
            }

            public function marry(object $husband): void
            {
                $this->husband = $husband;
                $this->family[] = $husband;
            }
        };

        $jsmith = new class() {
            /** @var string */
            public $name = 'John';

            /** @var string */
            public $last = 'Smith';

            /** @var int */
            public $age = 21;

            /** @var ?object */
            public $wife;

            /** @var object[] */
            public $family = [];

            /** @var int */
            private $salary = 123;

            public function getSalary(): int
            {
                return $this->salary;
            }

            public function getDebit(): int
            {
                return -$this->salary;
            }

            public function marry(object $wife): void
            {
                $this->wife = $wife;
                $this->family[] = $wife;
            }
        };

        $esmith->marry($jsmith);
        $jsmith->marry($esmith);

        $registry = new class() {
            /** @var array */
            public $persons = [];

            /** @var array */
            public $families = [];

            public function addFamily(string $key, array $persons): void
            {
                $this->families[$key] = $persons;
                $this->persons = array_merge($this->persons, $persons);
            }
        };

        $registry->addFamily('smith', [$esmith, $jsmith]);

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['name' => 'John', 'last' => 'Smith', 'age' => 21, 'wife' => $esmith],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'name' => 'John',
                'last' => 'Smith',
                'age'  => 21,
                'wife' => $esmith,
            ],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['name' => 'John', 'last' => 'Smith', 'age' => 21],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['name' => 'John', 'last' => 'Smith'],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['age' => 21],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['age' => 21, 'getSalary()' => 123, 'getDebit()' => -123],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'family' => [$esmith],
            ],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'persons'  => [
                    $esmith,
                    $jsmith,
                ],
                'families' => [
                    'smith' => [
                        $esmith,
                        $jsmith,
                    ],
                ],
            ],
            'actual' => $registry,
            'string' => 'object '.get_class($registry),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: array<string, mixed>,
     *      actual: object,
     *      string: string
     * }>
     */
    public static function provObjectPropertiesEqualButNotIdenticalTo(): iterable
    {
        $object = new class() {
            /** @var string */
            public $emptyString = '';

            /** @var mixed */
            public $null;

            /** @var string */
            public $string123 = '123';

            /** @var int */
            public $int321 = 321;

            /** @var bool */
            public $boolFalse = false;
        };

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'emptyString' => null,
                'null'        => '',
                'string123'   => 123,
                'int321'      => '321',
                'boolFalse'   => 0,
            ],
            'actual' => $object,
            'string' => 'object '.get_class($object),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: array<string, mixed>,
     *      actual: object,
     *      string: string
     * }>
     */
    public static function provObjectPropertiesNotEqualTo(): iterable
    {
        $hbrown = new class() {
            /** @var string */
            public $name = 'Helen';

            /** @var string */
            public $last = 'Brown';

            /** @var int */
            public $age = 44;
        };

        $esmith = new class() {
            /** @var string */
            public $name = 'Emily';

            /** @var string */
            public $last = 'Smith';

            /** @var int */
            public $age = 20;

            /** @var mixed */
            public $husband;

            /** @var array */
            public $family = [];

            /** @var int */
            private $salary = 98;

            public function getSalary(): int
            {
                return $this->salary;
            }

            public function getDebit(): int
            {
                return -$this->salary;
            }

            public function marry(object $husband): void
            {
                $this->husband = $husband;
                $this->family[] = $husband;
            }
        };

        $jsmith = new class() {
            /** @var string */
            public $name = 'John';

            /** @var string */
            public $last = 'Smith';

            /** @var int */
            public $age = 21;

            /** @var ?object */
            public $wife;

            /** @var object[] */
            public $family = [];

            /** @var int */
            private $salary = 123;

            public function getSalary(): int
            {
                return $this->salary;
            }

            public function getDebit(): int
            {
                return -$this->salary;
            }

            public function marry(object $wife): void
            {
                $this->wife = $wife;
                $this->family[] = $wife;
            }
        };

        $esmith->marry($jsmith);
        $jsmith->marry($esmith);

        $registry = new class() {
            /** @var array<object> */
            public $persons = [];

            /** @var array<array<object>> */
            public $families = [];

            /**
             * @psalm-param array<object> $persons
             */
            public function addFamily(string $key, array $persons): void
            {
                $this->families[$key] = $persons;
                $this->persons = array_merge($this->persons, $persons);
            }
        };

        $registry->addFamily('smith', [$esmith, $jsmith]);

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['name' => 'John', 'last' => 'Brown', 'age' => 21],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => null],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => 'Emily'],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => $hbrown],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['name' => 'John', 'last' => 'Brown'],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['age' => 19],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['age' => 21, 'getSalary()' => 1230],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'name' => 'John',
                'last' => 'Smith',
                'age'  => 21,
                'wife' => [
                    'name'        => 'Emily',
                    'last'        => 'Smith',
                    'age'         => 20,
                    'husband'     => [
                        'name'        => 'John',
                        'last'        => 'Smith',
                        'age'         => 21,
                        'getSalary()' => 123,
                    ],
                    'getSalary()' => 98,
                ],
            ],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'family' => [
                    ['name' => 'Emily', 'last' => 'Smith'],
                ],
            ],
            'actual' => $jsmith,
            'string' => 'object '.get_class($jsmith),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'persons'  => [
                    ['name' => 'Emily', 'last' => 'Smith'],
                    ['name' => 'John', 'last' => 'Smith'],
                ],
                'families' => [
                    'smith' => [
                        ['name' => 'Emily', 'last' => 'Smith'],
                        ['name' => 'John', 'last' => 'Smith'],
                    ],
                ],
            ],
            'actual' => $registry,
            'string' => 'object '.get_class($registry),
        ];

        /** @psalm-suppress MissingThrowsDocblock */
        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'persons'  => [
                    $esmith,
                    $jsmith,
                ],
                // the following must not match, as the 'families' property is an array, not an object.
                'families' => static::createConstraint([[
                    'smith' => [
                        $esmith,
                        $jsmith,
                    ],
                ]]),
            ],
            'actual' => $registry,
            'string' => 'object '.get_class($registry),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: array<string, mixed>,
     *      actual: mixed,
     *      string: string
     * }>
     */
    public static function provObjectPropertiesNotEqualToNonObject(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => 123,
            'string' => '123',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => 'arbitrary string',
            'string' => '\'arbitrary string\'',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => null,
            'string' => 'null',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => ['foo' => 'FOO'],
            'string' => 'array',
        ];
    }

    // @codeCoverageIgnoreEnd
}
