<?php declare(strict_types=1);

final class arrayValuesEqualToRecursiveTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ArrayValuesEqualToTrait;
    use \Tailors\PHPUnit\ArrayValuesTrait;

    private $array = [
        'int' => '123',
        'sub' => [
            'int' => 321,
            'bar' => 'BAR',
        ],
        'foo' => 'FOO'
    ];

    public function testSuccess(): void
    {
        $this->assertThat(
            $this->array,
            $this->arrayValuesEqualTo([
                'sub' => $this->arrayValues([
                    'int' => '321'
                ]),
                'int'  => 123,
            ])
        );
    }

    public function testFailure(): void
    {
        $this->assertThat(
            $this->array,
            $this->arrayValuesEqualTo([
                'sub' => [
                    'int' => '321'
                ],
                'int'  => 123,
            ])
        );
    }
}
