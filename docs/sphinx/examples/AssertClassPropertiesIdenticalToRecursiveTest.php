<?php declare(strict_types=1);

final class AssertClassPropertiesIdenticalToRecursiveTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ClassPropertiesIdenticalToTrait;
    use \Tailors\PHPUnit\ClassPropertiesTrait;

    public static $attribute = 123;
    public static $nested = self::class;
    public static $other = 321;

    public function testSuccess(): void
    {
        $this->assertClassPropertiesIdenticalTo([
            'attribute'  => 123,
            'nested' => $this->classProperties([
                'other' => 321,
            ])
        ], self::class);
    }

    public function testFailure(): void
    {
        $this->assertClassPropertiesIdenticalTo([
            'attribute'  => 123,
            'nested' => [
                'other' => 321,
            ]
        ], self::class);
    }
}
