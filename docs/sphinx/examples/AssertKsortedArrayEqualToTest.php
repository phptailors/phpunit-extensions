<?php declare(strict_types=1);

final class AssertKsortedArrayEqualToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\KsortedArrayEqualToTrait;

    public function testSuccess(): void
    {
        $this->assertKsortedArrayEqualTo(
            ['int' => 123,   1 => null, 'foo' => 'FOO'],
            ['foo' => 'FOO', 'int' => '123', 1 => '']
        );
    }

    public function testFailure(): void
    {
        $this->assertKsortedArrayEqualTo(
            ['int' => 123, 1 => '', 'foo' => 'BAR'],
            ['int' => 123, 1 => '', 'foo' => 'FOO']
        );
    }
}
