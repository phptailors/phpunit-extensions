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
 */
trait ProvObjectPropertiesTrait
{
    /**
     * @param mixed $args
     */
    abstract public static function createConstraint(...$args): Constraint;

    // @codeCoverageIgnoreStart

    public static function provObjectPropertiesIdenticalTo(): array
    {
        $esmith = new class() {
            public $name = 'Emily';
            public $last = 'Smith';
            public $age = 20;
            public $husband;
            public $family = [];
            private $salary = 98;

            public function getSalary()
            {
                return $this->salary;
            }

            public function getDebit()
            {
                return -$this->salary;
            }

            public function marry($husband)
            {
                $this->husband = $husband;
                $this->family[] = $husband;
            }
        };

        $jsmith = new class() {
            public $name = 'John';
            public $last = 'Smith';
            public $age = 21;
            public $wife;
            public $family = [];
            private $salary = 123;

            public function getSalary()
            {
                return $this->salary;
            }

            public function getDebit()
            {
                return -$this->salary;
            }

            public function marry($wife)
            {
                $this->wife = $wife;
                $this->family[] = $wife;
            }
        };

        $esmith->marry($jsmith);
        $jsmith->marry($esmith);

        $registry = new class() {
            public $persons = [];
            public $families = [];

            public function addFamily(string $key, array $persons)
            {
                $this->families[$key] = $persons;
                $this->persons = array_merge($this->persons, $persons);
            }
        };

        $registry->addFamily('smith', [$esmith, $jsmith]);

        return [
            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'age' => 21, 'wife' => $esmith],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age'  => 21,
                    'wife' => $esmith,
                ],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'age' => 21],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith'],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['age' => 21],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['age' => 21, 'getSalary()' => 123, 'getDebit()' => -123],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age'  => 21,
                    'wife' => static::createConstraint([
                        'name'        => 'Emily',
                        'last'        => 'Smith',
                        'age'         => 20,
                        'husband'     => $jsmith,
                        'getSalary()' => 98,
                    ]),
                ],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age'  => 21,
                    'wife' => static::createConstraint([
                        'name'    => 'Emily',
                        'last'    => 'Smith',
                        'age'     => 20,
                        'husband' => static::createConstraint([
                            'name'        => 'John',
                            'last'        => 'Smith',
                            'age'         => 21,
                            'getSalary()' => 123,
                        ]),
                        'getSalary()' => 98,
                    ]),
                ],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'family' => [$esmith],
                ],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'family' => [
                        static::createConstraint(['name' => 'Emily', 'last' => 'Smith']),
                    ],
                ],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'persons' => [
                        static::createConstraint(['name' => 'Emily', 'last' => 'Smith']),
                        static::createConstraint(['name' => 'John', 'last' => 'Smith']),
                    ],
                    'families' => [
                        'smith' => [
                            static::createConstraint(['name' => 'Emily', 'last' => 'Smith']),
                            static::createConstraint(['name' => 'John', 'last' => 'Smith']),
                        ],
                    ],
                ],
                'actual' => $registry,
                'string' => 'object '.get_class($registry),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'persons' => [
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
            ],
        ];
    }

    public static function provObjectPropertiesEqualButNotIdenticalTo(): array
    {
        $object = new class() {
            public $emptyString = '';
            public $null;
            public $string123 = '123';
            public $int321 = 321;
            public $boolFalse = false;
        };

        return [
            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'emptyString' => null,
                    'null'        => '',
                    'string123'   => 123,
                    'int321'      => '321',
                    'boolFalse'   => 0,
                ],
                'actual' => $object,
                'string' => 'object '.get_class($object),
            ],
        ];
    }

    public static function provObjectPropertiesNotEqualTo(): array
    {
        $hbrown = new class() {
            public $name = 'Helen';
            public $last = 'Brown';
            public $age = 44;
        };

        $esmith = new class() {
            public $name = 'Emily';
            public $last = 'Smith';
            public $age = 20;
            public $husband;
            public $family = [];
            private $salary = 98;

            public function getSalary()
            {
                return $this->salary;
            }

            public function getDebit()
            {
                return -$this->salary;
            }

            public function marry($husband)
            {
                $this->husband = $husband;
                $this->family[] = $husband;
            }
        };

        $jsmith = new class() {
            public $name = 'John';
            public $last = 'Smith';
            public $age = 21;
            public $wife;
            public $family = [];
            private $salary = 123;

            public function getSalary()
            {
                return $this->salary;
            }

            public function getDebit()
            {
                return -$this->salary;
            }

            public function marry($wife)
            {
                $this->wife = $wife;
                $this->family[] = $wife;
            }
        };

        $esmith->marry($jsmith);
        $jsmith->marry($esmith);

        $registry = new class() {
            public $persons = [];
            public $families = [];

            public function addFamily(string $key, array $persons)
            {
                $this->families[$key] = $persons;
                $this->persons = array_merge($this->persons, $persons);
            }
        };

        $registry->addFamily('smith', [$esmith, $jsmith]);

        return [
            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Brown', 'age' => 21],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => null],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => 'Emily'],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Smith', 'wife' => $hbrown],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['name' => 'John', 'last' => 'Brown'],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['age' => 19],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['age' => 21, 'getSalary()' => 1230],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'name' => 'John',
                    'last' => 'Smith',
                    'age'  => 21,
                    'wife' => [
                        'name'    => 'Emily',
                        'last'    => 'Smith',
                        'age'     => 20,
                        'husband' => [
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
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'family' => [
                        ['name' => 'Emily', 'last' => 'Smith'],
                    ],
                ],
                'actual' => $jsmith,
                'string' => 'object '.get_class($jsmith),
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'persons' => [
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
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => [
                    'persons' => [
                        $esmith,
                        $jsmith,
                    ],
                    // the following must not match, as the 'families' property is an array, not an object.
                    'families' => static::createConstraint([
                        'smith' => [
                            $esmith,
                            $jsmith,
                        ],
                    ]),
                ],
                'actual' => $registry,
                'string' => 'object '.get_class($registry),
            ],
        ];
    }

    public static function provObjectPropertiesNotEqualToNonObject(): array
    {
        return [
            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => 123,
                'string' => '123',
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => 'arbitrary string',
                'string' => '\'arbitrary string\'',
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => null,
                'string' => 'null',
            ],

            'ProvObjectPropertiesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => ['foo' => 'FOO'],
                'string' => 'array',
            ],
        ];
    }

    // @codeCoverageIgnoreEnd
}
