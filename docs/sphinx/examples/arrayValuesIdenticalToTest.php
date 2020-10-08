<?php declare(strict_types=1);

final class arrayValuesIdenticalToTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ArrayValuesIdenticalToTrait;

    public function testSuccess(): void
    {
        $this->assertThat(                ['int' => 123, 1 => null, 'foo' => 'FOO'],
            $this->arrayValuesIdenticalTo(['int' => 123, 1 => null])
        );
    }

    public function testFailure(): void
    {
        $this->assertThat(                ['int' => 123, 1 => ''],
            $this->arrayValuesIdenticalTo(['int' => 123, 1 => null])
        );
    }
}
