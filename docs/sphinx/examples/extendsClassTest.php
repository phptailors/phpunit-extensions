<?php declare(strict_types=1);

final class extendsClassTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\ExtendsClassTrait;

    public function testExtendsClass(): void
    {
        $this->assertThat(\RuntimeException::class, $this->extendsClass(\Exception::class));
        $this->assertThat(new \RuntimeException(), $this->extendsClass(\Exception::class));
    }

    public function testExtendsClassFailure(): void
    {
        $this->assertThat(self::class, $this->extendsClass(\Exception::class));
    }
}
