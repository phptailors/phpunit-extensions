<?php declare(strict_types=1);

final class AssertArrayValuesEqualToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ArrayValuesEqualToTrait;

    public function testSuccess(): void
    {
        $this->assertArrayValuesEqualTo(
            ['int' => 123,   1 => null],
            ['int' => '123', 1 => '', 'foo' => 'FOO']
        );
    }

    public function testFailure(): void
    {
        $this->assertArrayValuesEqualTo(
            ['int' => 123,   1 => null, 'foo' => 'BAR'],
            ['int' => '123', 1 => '',   'foo' => 'FOO']
        );
    }
}
