[![Unit Tests](https://github.com/phptailors/phpunit-extensions/actions/workflows/unit_test.yml/badge.svg)](https://github.com/phptailors/phpunit-extensions/actions/workflows/unit_test.yml)
[![Code Quality](https://github.com/phptailors/phpunit-extensions/actions/workflows/code_quality.yml/badge.svg)](https://github.com/phptailors/phpunit-extensions/actions/workflows/code_quality.yml)
[![Docs Tests](https://github.com/phptailors/phpunit-extensions/actions/workflows/docs_test.yml/badge.svg)](https://github.com/phptailors/phpunit-extensions/actions/workflows/docs_test.yml)
[![Docs Deploy](https://github.com/phptailors/phpunit-extensions/actions/workflows/docs_deploy.yml/badge.svg)](https://github.com/phptailors/phpunit-extensions/actions/workflows/docs_deploy.yml)
[![Type Coverage](https://shepherd.dev/github/phptailors/phpunit-extensions/coverage.svg)](https://shepherd.dev/github/phptailors/phpunit-extensions)
[![Code Coverage](https://codecov.io/gh/phptailors/phpunit-extensions/branch/master/graph/badge.svg?token=D1RZ1XLBIC)](https://codecov.io/gh/phptailors/phpunit-extensions)

# PHPUnit extensions

Additional constraints and assertions for use with [PHPUnit](https://phpunit.de).

## Why?

Feel like having certain tests written with less effort? Or need an assertion, but it's not provided by PHPUnit? Here is where we trying to help. You just install an extension package, use a trait providing given assertion/constraint and use it in your test.

## Quick example

Let's say we have ``Syntax::PERSON`` regular expression with defined capture groups, and we want to test it

**src/Syntax.php**:

```php
<?php declare(strict_types=1);

namespace Example;

final class Syntax
{
    public const PERSON = '/^\\s*(?<name>\\w+)\\s+(?<surname>\\w+)(?:,\\s+(?<age>\\w+))?\\s*/';
}
```

For that, we just install phpunit-regexp package (and, of course, the PHPUnit itself):

```console
john@pc:$ composer require --dev phptailors/phpunit-regexp
john@pc:$ composer require --dev phpunit/phpunit
john@pc:$ mkdir -p src tests
```

and write test such as the following:

```php
<?php declare(strict_types=1);

namespace Example;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\HasPregCapturesTrait;

final class SyntaxTest extends TestCase
{
    use HasPregCapturesTrait;

    /**
     * @return array<int, array<string,mixed>>
     */
    public static function provideSyntaxPerson(): array
    {
        return [
            ['John',              ['name' => false,  'surname' => false,   'age' => false]],
            [' John   Smith ',    ['name' => 'John', 'surname' => 'Smith', 'age' => false]],
            ['John Smith, 24',    ['name' => 'John', 'surname' => 'Smith', 'age' => '24']],
            ['John Smith, 24yrs', ['name' => 'John', 'surname' => 'Smith', 'age' => '24']],
        ];
    }

    /**
     * @param array<string,mixed> $expect
     */
    #[DataProvider('provideSyntaxPerson')]
    public function testSyntaxPerson(string $input, array $expect): void
    {
        preg_match(Syntax::PERSON, $input, $captures, PREG_UNMATCHED_AS_NULL);
        $this->assertHasPregCaptures($expect, $captures);
    }
}
```

```console
john@pc:$ vendor/bin/phpunit tests/ExampleRegexpTest.php
PHPUnit 11.1.3 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.6

...F                                                                4 / 4 (100%)

Time: 00:00.010, Memory: 8.00 MB

There was 1 failure:

1) Example\SyntaxTest::testSyntaxPerson with data set #3 ('John Smith, 24yrs', ['John', 'Smith', '24'])
Failed asserting that array has expected PCRE capture groups.
--- Expected
+++ Actual
@@ @@
 Array &0 [
     'name' => 'John',
     'surname' => 'Smith',
-    'age' => '24',
+    'age' => '24yrs',
 ]


tests/SyntaxTest.php:33

FAILURES!
Tests: 4, Assertions: 4, Failures: 1.
```



## Packages

The [phptailors/phpunit-extensions](https://github.com/phptailors/phpunit-extensions) is a monorepo, where we develop the following packages

#### [phpunit-arrays](https://packagist.org/packages/phptailors/phpunit-arrays)

Assertions for array contents testing (not provided by PHPUnit),

```shell
composer require --dev phptailors/phpunit-arrays
```

#### [phpunit-inheritance](https://packagist.org/packages/phptailors/phpunit-inheritance)

Testing class hierarches: whether a class implements interface, uses trait, etc.,

```shell
composer require --dev phptailors/phpunit-arrays
```

#### [phpunit-methods](https://packagist.org/packages/phptailors/phpunit-methods)

Ensuring methods' existence and modifiers (is it private? is it final? or, maybe, is it static?).

```shell
composer require --dev phptailors/phpunit-arrays
```

#### [phpunit-properties](https://packagist.org/packages/phptailors/phpunit-properties)

Testing for objects' state (property values checked using convenient array-like notation).

```shell
composer require --dev phptailors/phpunit-arrays
```

#### [phpunit-regexp](https://packagist.org/packages/phptailors/phpunit-regexp)

Testing capture groups in regular expressions, useful for projects that implement tokenizers or parsers.

```shell
composer require --dev phptailors/phpunit-arrays
```

## Versioning

We maintain several version branches, e.g. ``1.x``, ``2.x``, etc, mainly for compatibility with different versions of PHPUnit, PHP and dependent libraries (such as [sebastian/exporter](https://github.com/sebastianbergmann/exporter) or [sebastian/recursion-context](https://github.com/sebastianbergmann/recursion-context)). We do not define version constraints for PHPUnit in our composer.json files, don't even require it here. The choice is left to you - you install your preferred version of PHPUnit in your project along with one or more of our extension packages. The table below may be taken as a guidance, suggesting which version of phpunit-extensions is best to chose for particular PHPUnit and PHP version. Pickup most recent, suitable version. The table resembles our version matrix used in automated tests

|  PHPUnit            | PHP                     | phpunit-extensions |
| --------------------| ----------------------- | ------------------ |
| ``^9.5.5 \|\| ^10`` | ``7.3,7.4,8.0,8.1,8.2`` | ``1.x ``           |
| ``^9.5.5 \|\| ^10`` | ``8.1,8.2``             | ``2.x``            |
| ``^11.0.1``         | ``8.2,8.3``             | ``3.x``            |


## Online documentation

- https://phptailors.github.io/phpunit-extensions
- https://phptailors.github.io/phpunit-extensions/docs
