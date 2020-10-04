<?php declare(strict_types=1);

final class AssertExtendsClassTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ExtendsClassTrait;

    public function testAssertExtendsClass(): void
    {
        $this->assertExtendsClass(\Exception::class, \RuntimeException::class);
        $this->assertExtendsClass(\Exception::class, new \RuntimeException());
    }

    public function testAssertExtendsClassFailure(): void
    {
        $this->assertExtendsClass(\Exception::class, self::class);
    }
}
