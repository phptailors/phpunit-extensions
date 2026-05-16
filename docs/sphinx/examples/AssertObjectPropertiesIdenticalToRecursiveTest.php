<?php declare(strict_types=1);

final class AssertObjectPropertiesIdenticalToRecursiveTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;
    use \Tailors\PHPUnit\ObjectPropertiesTrait;

    public $attribute = 123;
    public $other = 321;

    public function nested(): \Exception
    {
        return new \Exception('FOO', 456);
    }

    public function testSuccess(): void
    {
        $this->assertObjectPropertiesIdenticalTo([
            'attribute'  => 123,
            'nested()' => $this->objectProperties([
                'getMessage()' => 'FOO',
                'getCode()' => 456,
            ])
        ], $this);
    }

    public function testFailure(): void
    {
        $this->assertObjectPropertiesIdenticalTo([
            'attribute'  => 123,
            'nested()' => [
                'getMessage()' => 'FOO',
                'getCode()' => 456,
            ]
        ], $this);
    }
}
