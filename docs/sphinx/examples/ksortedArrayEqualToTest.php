<?php declare(strict_types=1);

final class ksortedArrayEqualToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\KsortedArrayEqualToTrait;

    public function testSuccess(): void
    {
        $this->assertThat(             ['int' => '123', 1 => null,'foo' => 'FOO'],
            $this->ksortedArrayEqualTo(['foo' => 'FOO', 'int' => 123, 1 => '' ])
        );
    }

    public function testFailure(): void
    {
        $this->assertThat(             ['int' => '123', 1 => '', 'foo' => 'FOO'],
            $this->ksortedArrayEqualTo(['int'  => 123,  1 => '', 'foo' => 'BAR'])
        );
    }
}
