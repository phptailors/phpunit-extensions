<?php declare(strict_types=1);

final class implementsInterfaceTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ImplementsInterfaceTrait;

    public function testImplementsInterface(): void
    {
        $this->assertThat(\RuntimeException::class, $this->implementsInterface(\Throwable::class));
        $this->assertThat(new \RuntimeException(), $this->implementsInterface(\Throwable::class));
    }

    public function testImplementsInterfaceFailure(): void
    {
        $this->assertThat(self::class, $this->implementsInterface(\Throwable::class));
    }
}
