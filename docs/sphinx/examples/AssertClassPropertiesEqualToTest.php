<?php declare(strict_types=1);

final class AssertClassPropertiesEqualToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ClassPropertiesEqualToTrait;

    public static $attribute = 123;

    public static function getValue(): int
    {
        return 321;
    }

    public function testSuccess(): void
    {
        // assert that:
        $this->assertClassPropertiesEqualTo([
            'attribute'  => '123',  // - self::$attribute equals '123' (ok)
            'getValue()' => '321',  // - self::getValue() equals '321' (ok)
        ], self::class);
    }

    public function testFailure(): void
    {
        // assert that:
        $this->assertClassPropertiesEqualTo([
            'attribute'  => '123',  // - self::$attribute equals '123' (ok),
            'getValue()' => null,   // - self::$getValue() is 321, not equals null (fail)
        ], self::class);
    }
}
