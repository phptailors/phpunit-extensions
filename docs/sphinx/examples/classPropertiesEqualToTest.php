<?php declare(strict_types=1);

final class classPropertiesEqualToTest extends \PHPUnit\Framework\TestCase
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
        $this->assertThat(self::class, $this->classPropertiesEqualTo([
            'attribute'  => '123', // - self::$attribute is 123, equals '123' (ok)
            'getValue()' => 321,    // - self::getValue() is 321 (ok)
        ]));
    }

    public function testFailure(): void
    {
        // assert that:
        $this->assertThat(self::class, $this->classPropertiesEqualTo([
            'attribute'  => '123',   // - self::$attribute is 123, equals '123' (ok)
            'getValue()' => null,     // - self::getValue() is 321, not equals null (fail)
        ]));
    }
}
