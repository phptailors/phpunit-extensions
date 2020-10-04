<?php declare(strict_types=1);

trait ExampleTraitForAssertUsesTraitTest
{
}

final class AssertUsesTraitTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\UsesTraitTrait;
    use ExampleTraitForAssertUsesTraitTest;

    public function testAssertUsesTrait(): void
    {
        $this->assertUsesTrait(ExampleTraitForAssertUsesTraitTest::class, self::class);
        $this->assertUsesTrait(ExampleTraitForAssertUsesTraitTest::class, $this);
    }

    public function testAssertUsesTraitFailure(): void
    {
        $this->assertUsesTrait(ExampleTraitForAssertUsesTraitTest::class, \RuntimeException::class);
    }
}
