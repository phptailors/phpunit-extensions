<?php declare(strict_types=1);

final class objectPropertiesIdenticalToRecursiveTest extends \PHPUnit\Framework\TestCase
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
        $this->assertThat($this, $this->objectPropertiesIdenticalTo([
            'attribute'  => 123,
            'nested()' => $this->objectProperties([
                'getMessage()' => 'FOO',
                'getCode()' => 456,
            ])
        ]));
    }

    public function testFailure(): void
    {
        $this->assertThat($this, $this->objectPropertiesIdenticalTo([
            'attribute'  => 123,
            'nested()' => [
                'getMessage()' => 'FOO',
                'getCode()' => 456,
            ]
        ]));
    }
}
