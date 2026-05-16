<?php declare(strict_types=1);

final class arrayValuesIdenticalToRecursiveTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ArrayValuesIdenticalToTrait;
    use \Tailors\PHPUnit\ArrayValuesTrait;

    private $array = [
        'int' => 123,
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
            $this->arrayValuesIdenticalTo([
                'sub' => $this->arrayValues([
                    'int' => 321
                ]),
                'int'  => 123,
            ])
        );
    }

    public function testFailure(): void
    {
        $this->assertThat(
            $this->array,
            $this->arrayValuesIdenticalTo([
                'sub' => [
                    'int' => 321
                ],
                'int'  => 123,
            ])
        );
    }
}
