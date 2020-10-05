---
title: "Quick start"
date: 2020-12-18T11:20:38+01:00
categories:
  - blog
tags:
  - Quick Start
  - docs
---

In this guide we'll create a simple project and write a test asserting that an
array has given values under certain keys.

Create project directories

```console
user@pc:$ mkdir -p /tmp/myproj/tests && cd /tmp/myproj
```

Add the following ``composer.json`` file

```json
{
    "name": "myproj/myproj",
    "minimum-stability": "dev",
    "prefer-stable": true,
    "authors": [
        {
            "name": "My Project",
            "email": "myproj@myproj.org"
        }
    ],
    "require-dev": {
        "phpunit/phpunit": "^9.5.0",
        "phptailors/phpunit-arrays": "dev-master"
    },
    "autoload-dev": {
        "psr-4": {
          "MyProj\\": [
            "src/",
            "tests/"
          ]
        }
    }
}
```

Intall composer packages

```console
user@pc:$ composer install
```

Add the following ``tests/ArrayTest.php`` file

```php
<?php declare(strict_types=1);

namespace MyProj;

class ArrayTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ArrayValuesIdenticalToTrait;

    public function testArrayValuesIdenticalTo(): void
    {
        $array = [
            'a'    => 'A',
            'b'    => 'B',
            123    => '123',
            'null' => null,
        ];

        $this->assertArrayValuesIdenticalTo([
            'a' => 'A',
            'b' => 'B',
        ], $array);

        $this->assertArrayValuesIdenticalTo([
            123    => '123',
            'null' => null,
        ], $array);

        // '123' (string) is not identical to 123 (int)
        $this->assertNotArrayValuesIdenticalTo([
            123    => 123,
            'null' => null
        ], $array);
    }
}
```

Run PHPUnit

```console
user@pc:$ vendor/bin/phpunit tests/ArrayTest.php
PHPUnit 9.5.0 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 00:00.005, Memory: 4.00 MB

OK (1 test, 3 assertions)
```

[phpunit]: https://phpunit.de
