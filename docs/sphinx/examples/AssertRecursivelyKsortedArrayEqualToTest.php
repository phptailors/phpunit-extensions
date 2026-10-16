<?php declare(strict_types=1);

final class AssertRecursivelyKsortedArrayEqualToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\RecursivelyKsortedArrayEqualToTrait;

    public function testSuccess(): void
    {
        $this->assertRecursivelyKsortedArrayEqualTo(
            ['int' => 123,   'arr' => [1 => null, 'foo' => 'FOO']],
            ['arr' => ['foo' => 'FOO',  1 => ''], 'int' => '123']
        );
    }

    public function testFailure(): void
    {
        $this->assertRecursivelyKsortedArrayEqualTo(
            ['int' => 123, 'arr' => [1 => '', 'foo' => 'BAR']],
            ['int' => 123, 'arr' => [1 => '', 'foo' => 'FOO']]
        );
    }
}
