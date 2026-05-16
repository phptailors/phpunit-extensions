<?php declare(strict_types=1);

final class AssertArrayValuesIdenticalToRecursiveTest extends \PHPUnit\Framework\TestCase
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
        $this->assertArrayValuesIdenticalTo([
            'sub' => $this->arrayValues([
                'int' => 321
            ]),
            'int'  => 123,
        ], $this->array);
    }

    public function testFailure(): void
    {
        $this->assertArrayValuesIdenticalTo([
            'sub' => [
                'int' => 321
            ],
            'int'  => 123,
        ], $this->array);
    }
}
