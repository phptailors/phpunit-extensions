<?php declare(strict_types=1);

trait ExampleTraitForUsesTraitTest
{
}

final class UsesTraitTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\UsesTraitTrait;
    use ExampleTraitForUsesTraitTest;

    public function testUsesTrait(): void
    {
        $this->assertThat(self::class, $this->usesTrait(ExampleTraitForUsesTraitTest::class));
        $this->assertThat($this, $this->usesTrait(ExampleTraitForUsesTraitTest::class));
    }

    public function testUsesTraitFailure(): void
    {
        $this->assertThat(\RuntimeException::class, $this->usesTrait(ExampleTraitForUsesTraitTest::class));
    }
}
