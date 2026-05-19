<?php declare(strict_types=1);

final class recursivelyKsortedArrayIdenticalToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\RecursivelyKsortedArrayIdenticalToTrait;

    public function testSuccess(): void
    {
        $this->assertThat(['int' => 123, 'arr' => [1 => null, 'foo' => 'FOO']],
            $this->recursivelyKsortedArrayIdenticalTo(['arr' => ['foo' => 'FOO', 1 => null], 'int' => 123])
        );
    }

    public function testFailure(): void
    {
        $this->assertThat(['int' => 123, 'arr' => [1 => '', 'foo' => 'FOO']],
            $this->recursivelyKsortedArrayIdenticalTo(['arr' => ['foo' => 'FOO', 1 => null], 'int' => 123])
        );
    }
}
