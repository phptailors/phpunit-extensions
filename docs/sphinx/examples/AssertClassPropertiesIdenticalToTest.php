<?php declare(strict_types=1);

final class AssertClassPropertiesIdenticalToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ClassPropertiesIdenticalToTrait;

    public static $attribute = 123;

    public static function getValue(): int
    {
        return 321;
    }

    public function testSuccess(): void
    {
        // assert that:
        $this->assertClassPropertiesIdenticalTo([
            'attribute'  => 123,   // - self::$attribute is 123 (ok)
            'getValue()' => 321,   // - self::getValue() is 321 (ok)
        ], self::class);
    }

    public function testFailure(): void
    {
        // assert that:
        $this->assertClassPropertiesIdenticalTo([
            'attribute'  => '123', // - self::$attribute is 123, not '123' (fail),
            'getValue()' => null,  // - self::getValue() is 321, not null (fail)
        ], self::class);
    }
}
