<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Examples\Inheritance\ExampleClassNotUsingTrait;
use Tailors\PHPUnit\Examples\Inheritance\ExampleClassUsingTrait;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTraitUsingTrait;
use Tailors\PHPUnit\Inheritance\AbstractConstraint;
use Tailors\PHPUnit\Inheritance\ConstraintImplementationTrait;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(InheritanceConstraintTestTrait::class)]
#[CoversClass(UsesTrait::class)]
#[CoversClass(AbstractConstraint::class)]
#[CoversClass(ConstraintImplementationTrait::class)]
final class UsesTraitTest extends TestCase
{
    use InheritanceConstraintTestTrait;

    // required by InheritanceConstraintTestTrait
    public static function provFailureDescriptionOfCustomUnaryOperator(): iterable
    {
        return [
            'UsesTraitTest.php:'.__LINE__ => [
                'constraint' => UsesTrait::create(ExampleTrait::class),
                'subject'    => \Exception::class,
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => '/Exception uses trait '.preg_quote(ExampleTrait::class, '/').'/',
                ],
            ],
        ];
    }

    // required by InheritanceConstraintTestTrait
    public static function provFailureDescriptionOfLogicalNotOperator(): iterable
    {
        return [
            'UsesTraitTest.php:'.__LINE__ => [
                'constraint' => UsesTrait::create(ExampleTrait::class),
                'subject'    => ExampleClassUsingTrait::class,
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => sprintf(
                        '/%s does not use trait %s/',
                        preg_quote(ExampleClassUsingTrait::class, '/'),
                        preg_quote(ExampleTrait::class, '/')
                    ),
                ],
            ],
        ];
    }

    public static function provUsesTrait(): array
    {
        return [
            'UsesTraitTest.php:'.__LINE__ => [
                'trait'   => ExampleTrait::class,
                'subject' => ExampleClassUsingTrait::class,
            ],
            'UsesTraitTest.php:'.__LINE__ => [
                'trait'   => ExampleTrait::class,
                'subject' => new ExampleClassUsingTrait(),
            ],
            'UsesTraitTest.php:'.__LINE__ => [
                'trait'   => ExampleTrait::class,
                'subject' => ExampleTraitUsingTrait::class,
            ],
            // case-insensitive match
            'UsesTraitTest.php:'.__LINE__ => [
                'trait'   => 'tailors\\PhPunit\\eXamples\\inhEritance\eXampletRait',
                'subject' => 'tailors\\PhPunit\\eXamples\\inhEritance\eXampleclAssuSingtRait',
            ],
            'UsesTraitTest.php:'.__LINE__ => [
                'trait'   => 'tailors\\PhPunit\\eXamples\\inhEritance\eXampletRait',
                'subject' => new ExampleClassUsingTrait(),
            ],
            'UsesTraitTest.php:'.__LINE__ => [
                'trait'   => 'tailors\\PhPunit\\eXamples\\inhEritance\eXampletRait',
                'subject' => 'tailors\\PhPunit\\eXamples\\inhEritance\eXampletRaituSingtRait',
            ],
        ];
    }

    public static function provNotUsesTrait(): array
    {
        $template = 'Failed asserting that %s uses trait %s.';

        return [
            'UsesTraitTest.php:'.__LINE__ => [
                'trait'   => ExampleTrait::class,
                'subject' => ExampleClassNotUsingTrait::class,
                'message' => sprintf($template, ExampleClassNotUsingTrait::class, ExampleTrait::class),
            ],
            'UsesTraitTest.php:'.__LINE__ => [
                'trait'   => ExampleTrait::class,
                'subject' => new ExampleClassNotUsingTrait(),
                'message' => sprintf($template, 'object '.ExampleClassNotUsingTrait::class, ExampleTrait::class),
            ],
            'UsesTraitTest.php:'.__LINE__ => [
                'trait'   => ExampleTrait::class,
                'subject' => 'lorem ipsum',
                'message' => sprintf($template, "'lorem ipsum'", ExampleTrait::class),
            ],
            'UsesTraitTest.php:'.__LINE__ => [
                'trait'   => ExampleTrait::class,
                'subject' => 123,
                'message' => sprintf($template, '123', ExampleTrait::class),
            ],
        ];
    }

    public static function provConstraintThrowsInvalidArgumentException(): array
    {
        $message = '/Argument 1 passed to \S+ must be a trait-string/';

        return [
            'UsesTraitTest.php:'.__LINE__ => [
                'argument' => 'non-trait string',
                'message'  => $message,
            ],

            'UsesTraitTest.php:'.__LINE__ => [
                'argument' => \Exception::class,
                'message'  => $message,
            ],

            'UsesTraitTest.php:'.__LINE__ => [
                'argument' => \Throwable::class,
                'message'  => $message,
            ],
        ];
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provUsesTrait')]
    public function testConstraintSucceeds(string $trait, $subject): void
    {
        $constraint = UsesTrait::create($trait);

        self::assertTrue($constraint->evaluate($subject, '', true));
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provNotUsesTrait')]
    public function testConstraintFails(string $trait, $subject, string $message): void
    {
        $constraint = UsesTrait::create($trait);

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        $constraint->evaluate($subject);
    }

    #[DataProvider('provConstraintThrowsInvalidArgumentException')]
    public function testConstraintThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageMatches($message);

        UsesTrait::create($argument);
    }
}
