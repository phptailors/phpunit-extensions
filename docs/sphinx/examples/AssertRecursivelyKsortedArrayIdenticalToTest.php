<?php declare(strict_types=1);

final class AssertRecursivelyKsortedArrayIdenticalToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\RecursivelyKsortedArrayIdenticalToTrait;

    public function testSuccess(): void
    {
        $this->assertRecursivelyKsortedArrayIdenticalTo(
            ['int' => 123, 'arr' => [1 => null, 'foo' => 'FOO']],
            ['arr' => ['foo' => 'FOO', 1 => null], 'int' => 123]
        );
    }

    public function testFailure(): void
    {
        $this->assertRecursivelyKsortedArrayIdenticalTo(
            ['int' =>  123,  'arr' => [1 => null, 'foo' => 'FOO']],
            ['int' => '123', 'arr' => [1 => '',   'foo' => 'FOO']]
        );
    }
}
