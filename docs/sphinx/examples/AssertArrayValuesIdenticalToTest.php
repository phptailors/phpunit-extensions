<?php declare(strict_types=1);

final class AssertArrayValuesIdenticalToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ArrayValuesIdenticalToTrait;

    public function testSuccess(): void
    {
        $this->assertArrayValuesIdenticalTo(
            ['int' => 123, 1 => null],
            ['int' => 123, 1 => null, 'foo' => 'FOO']
        );
    }

    public function testFailure(): void
    {
        $this->assertArrayValuesIdenticalTo(
            ['int' =>  123,  1 => null],
            ['int' => '123', 1 => '']
        );
    }
}
