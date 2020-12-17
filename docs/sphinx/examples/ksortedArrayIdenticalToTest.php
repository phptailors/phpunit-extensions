<?php declare(strict_types=1);

final class ksortedArrayIdenticalToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\KsortedArrayIdenticalToTrait;

    public function testSuccess(): void
    {
        $this->assertThat(                 ['int' => 123, 1 => null, 'foo' => 'FOO'],
            $this->ksortedArrayIdenticalTo(['foo' => 'FOO', 'int' => 123, 1 => null])
        );
    }

    public function testFailure(): void
    {
        $this->assertThat(                 ['int' => 123, 1 => '', 'foo' => 'FOO'],
            $this->ksortedArrayIdenticalTo(['int' => 123, 1 => null, 'foo' => 'FOO'])
        );
    }
}
