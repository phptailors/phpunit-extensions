<?php declare(strict_types=1);

final class AssertClassPropertiesEqualToRecursiveTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ClassPropertiesEqualToTrait;
    use \Tailors\PHPUnit\ClassPropertiesTrait;

    public static $attribute = 123;
    public static $nested = self::class;
    public static $other = 321;

    public function testSuccess(): void
    {
        $this->assertClassPropertiesEqualTo([
            'attribute'  => '123',
            'nested' => $this->classProperties([
                'other' => '321',
            ])
        ], self::class);
    }

    public function testFailure(): void
    {
        $this->assertClassPropertiesEqualTo([
            'attribute'  => '123',
            'nested' => [
                'other' => '321',
            ]
        ], self::class);
    }
}
