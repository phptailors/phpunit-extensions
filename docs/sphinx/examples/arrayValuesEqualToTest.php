<?php declare(strict_types=1);

final class arrayValuesEqualToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ArrayValuesEqualToTrait;

    public function testSuccess(): void
    {
        $this->assertThat(            ['int' => '123', 1 => null,'foo' => 'FOO'],
            $this->arrayValuesEqualTo(['int'  => 123,  1 => '' ])
        );
    }

    public function testFailure(): void
    {
        $this->assertThat(            ['int' => '123', 1 => null,'foo' => 'FOO'],
            $this->arrayValuesEqualTo(['int'  => 123,  1 => '',  'foo' => 'BAR'])
        );
    }
}
