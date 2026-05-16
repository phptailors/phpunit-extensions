<?php declare(strict_types=1);

final class AssertArrayValuesEqualToRecursiveTest extends \PHPUnit\Framework\TestCase
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
        $this->assertArrayValuesEqualTo([
            'sub' => $this->arrayValues([
                'int' => '321'
            ]),
            'int'  => 123,
        ], $this->array);
    }

    public function testFailure(): void
    {
        $this->assertArrayValuesEqualTo([
            'sub' => [
                'int' => '321'
            ],
            'int'  => 123,
        ], $this->array);
    }
}
