<?php declare(strict_types=1);

final class AssertKsortedArrayIdenticalToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\KsortedArrayIdenticalToTrait;

    public function testSuccess(): void
    {
        $this->assertKsortedArrayIdenticalTo(
            ['int' => 123, 1 => null, 'foo' => 'FOO'],
            ['foo' => 'FOO', 1 => null, 'int' => 123]
        );
    }

    public function testFailure(): void
    {
        $this->assertKsortedArrayIdenticalTo(
            ['int' =>  123,  1 => null, 'foo' =>'FOO'],
            ['int' => '123', 1 => '', 'foo' => 'FOO']
        );
    }
}
