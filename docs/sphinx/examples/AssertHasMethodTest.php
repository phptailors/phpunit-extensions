<?php declare(strict_types=1);

abstract class ClassWithSomeMethods
{
    abstract protected function abstractProtectedMethod();
    public function publicMethod() { }
}

final class AssertHasMethodTest extends PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\HasMethodTrait;

    public function testAssertHasMethod(): void
    {
        // ClassWithSomeMethods::publicMethod
        $this->assertHasMethod('publicMethod', ClassWithSomeMethods::class);
        $this->assertHasMethod('public function publicMethod', ClassWithSomeMethods::class);
        $this->assertHasMethod('!abstract !final public function publicMethod', ClassWithSomeMethods::class);

        // ClassWithSomeMethods::abstractProtectedMethod
        $this->assertHasMethod('protected function abstractProtectedMethod', ClassWithSomeMethods::class);
        $this->assertHasMethod('abstract function abstractProtectedMethod', ClassWithSomeMethods::class);
        $this->assertHasMethod('abstract protected function abstractProtectedMethod', ClassWithSomeMethods::class);


        $this->assertNotHasMethod('!abstract function abstractProtectedMethod', ClassWithSomeMethods::class);
    }

    public function testAssertHasMethodFailure(): void
    {
        $this->assertHasMethod('!abstract function abstractProtectedMethod', ClassWithSomeMethods::class);
    }
}
