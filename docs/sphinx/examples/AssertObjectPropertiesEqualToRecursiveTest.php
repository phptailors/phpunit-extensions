<?php declare(strict_types=1);

final class AssertObjectPropertiesEqualToRecursiveTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ObjectPropertiesEqualToTrait;
    use \Tailors\PHPUnit\ObjectPropertiesTrait;

    public $attribute = 123;
    public $other = 321;

    public function nested(): \Exception
    {
        return new \Exception('FOO', 456);
    }

    public function testSuccess(): void
    {
        $this->assertObjectPropertiesEqualTo([
            'attribute'  => '123',
            'nested()' => $this->objectProperties([
                'getMessage()' => 'FOO',
                'getCode()' => '456',
            ])
        ], $this);
    }

    public function testFailure(): void
    {
        $this->assertObjectPropertiesEqualTo([
            'attribute'  => '123',
            'nested()' => [
                'getMessage()' => 'FOO',
                'getCode()' => '456',
            ]
        ], $this);
    }
}
