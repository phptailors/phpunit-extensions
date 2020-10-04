<?php declare(strict_types=1);

final class AssertImplementsInterfaceTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ImplementsInterfaceTrait;

    public function testAssertImplementsInterface(): void
    {
        $this->assertImplementsInterface(\Throwable::class, \RuntimeException::class);
        $this->assertImplementsInterface(\Throwable::class, new \RuntimeException());
    }

    public function testAssertImplementsInterfaceFailure(): void
    {
        $this->assertImplementsInterface(\Throwable::class, self::class);
    }
}
