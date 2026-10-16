<?php declare(strict_types=1);

final class recursivelyKsortedArrayEqualToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\RecursivelyKsortedArrayEqualToTrait;

    public function testSuccess(): void
    {
        $this->assertThat(['int' => '123', 'arr' => [1 => null, 'foo' => 'FOO']],
            $this->recursivelyKsortedArrayEqualTo(['arr' => ['foo' => 'FOO', 1 => ''], 'int' => 123 ])
        );
    }

    public function testFailure(): void
    {
        $this->assertThat(['int' => '123', 'arr' => [1 => '', 'foo' => 'FOO']],
            $this->recursivelyKsortedArrayEqualTo(['int' => 123,  'arr' => [1 => '', 'foo' => 'BAR']])
        );
    }
}
