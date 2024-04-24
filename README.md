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

Let's check if a ``$regexp`` matches sample strings and captures pieces of text as expected

```shell
composer require --dev phptailors/phpunit-regexp
composer require --dev phpunit/phpunit
mkdir tests && nvim tests/ExampleRegexpTest.php
```

```php
<?php declare(strict_types=1);

namespace Example;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\HasPregCapturesTrait;

final class ExampleRegexpTest extends TestCase
{
    use HasPregCapturesTrait;

    private static function regex(): string
    {
        return '/(?<name>\w+) (?<surname>\w+)(?:, (?<age>\d+))?/';
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function provideRegexMatchAndCaptures(): array
    {

        return [
            ['input' => 'John',           'expect' => ['name' => false,  'surname' => false,   'age' => false]],
            ['input' => ' John Smith ',   'expect' => ['name' => 'John', 'surname' => 'Smith', 'age' => false]],
            ['input' => 'John Smith, 24', 'expect' => ['name' => 'John', 'surname' => 'Smith', 'age' => false]],
        ];
    }

    /**
     * @param array<string, mixed> $expect
     */
    #[DataProvider('provideRegexMatchAndCaptures')]
    public function testRegexMatchAndCaptures(string $input, array $expect): void
    {
        preg_match(self::regex(), $input, $captures, PREG_UNMATCHED_AS_NULL);
        $this->assertHasPregCaptures($expect, $captures);
    }
}
```

```console
john@pc:$ vendor/bin/phpunit tests/ExampleRegexpTest.php
PHPUnit 11.1.3 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.6

..F                                                                 3 / 3 (100%)

Time: 00:00.014, Memory: 8.00 MB

There was 1 failure:

1) Example\ExampleRegexpTest::testRegexMatchAndCaptures with data set #2 ('John Smith, 24', ['John', 'Smith', false])
Failed asserting that array has expected PCRE capture groups.
--- Expected
+++ Actual
@@ @@
 Array &0 [
     'name' => 'John',
     'surname' => 'Smith',
-    'age' => false,
+    'age' => '24',
 ]

ExampleRegexpTest.php:38

FAILURES!
Tests: 3, Assertions: 3, Failures: 1.

```

There is more, see [documentation](https://phptailors.github.io/phpunit-extensions/docs).

## Packages

The [phptailors/phpunit-extensions](https://github.com/phptailors/phpunit-extensions) is a set of packages providing additional constraints and assertions that you may find handy in your project testing.

- [phptailors/phpunit-arrays](https://packagist.org/packages/phptailors/phpunit-arrays) - assertions for array contents testing (not provided by PHPUnit),
  ```shell
  composer require --dev phptailors/phpunit-arrays
  ```

- [phptailors/phpunit-inheritance](https://packagist.org/packages/phptailors/phpunit-inheritance) - testing class hierarches: whether a class implements interface, uses trait, etc.,
  ```shell
  composer require --dev phptailors/phpunit-arrays
  ```

- [phptailors/phpunit-methods](https://packagist.org/packages/phptailors/phpunit-methods) - ensuring methods' existence and modifiers (is it private? is it final? or, maybe, is it static?),
  ```shell
  composer require --dev phptailors/phpunit-arrays
  ```

- [phptailors/phpunit-properties](https://packagist.org/packages/phptailors/phpunit-properties) - testing for objects' state (property values checked using convenient array-like notation),
  ```shell
  composer require --dev phptailors/phpunit-arrays
  ```

- [phptailors/phpunit-regexp](https://packagist.org/packages/phptailors/phpunit-regexp) - testing regular expressions including capture groups, useful for projects that implement tokenizers or parsers,
  ```shell
  composer require --dev phptailors/phpunit-arrays
  ```


## Online documentation

- https://phptailors.github.io/phpunit-extensions
- https://phptailors.github.io/phpunit-extensions/docs
