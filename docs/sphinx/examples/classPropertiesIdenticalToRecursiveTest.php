<?php declare(strict_types=1);

final class classPropertiesIdenticalToRecursiveTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ClassPropertiesIdenticalToTrait;
    use \Tailors\PHPUnit\ClassPropertiesTrait;

    public static $attribute = 123;
    public static $nested = self::class;
    public static $other = 321;

    public function testSuccess(): void
    {
        $this->assertThat(self::class, $this->classPropertiesIdenticalTo([
            'attribute'  => 123,
            'nested' => $this->classProperties([
                'other' => 321,
            ])
        ]));
    }

    public function testFailure(): void
    {
        $this->assertThat(self::class, $this->classPropertiesIdenticalTo([
            'attribute'  => 123,
            'nested' => [
                'other' => 321,
            ]
        ]));
    }
}
