<?php declare(strict_types=1);

final class objectPropertiesEqualToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ObjectPropertiesEqualToTrait;

    public $attribute = 123;

    public function getValue(): int
    {
        return 321;
    }

    public function testSuccess(): void
    {
        // assert that:
        $this->assertThat($this, $this->objectPropertiesEqualTo([
            'attribute'  => '123', // - $this->attribute is 123, equals '123' (ok)
            'getValue()' => 321,    // - $this->getValue() is 321 (ok)
        ]));
    }

    public function testFailure(): void
    {
        // assert that:
        $this->assertThat($this, $this->objectPropertiesEqualTo([
            'attribute'  => '123',   // - $this->attribute is 123, equals '123' (ok)
            'getValue()' => null,     // - $this->getValue() is 321, not equals null (fail)
        ]));
    }
}
