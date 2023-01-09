<?php declare(strict_types=1);

abstract class ClassWithSomeMethods
{
    abstract protected function abstractProtectedMethod();
    public function publicMethod() { }
}

final class hasMethodTest extends PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\HasMethodTrait;

    public function testHasMethod(): void
    {
        // ClassWithSomeMethods::publicMethod
        $this->assertThat(ClassWithSomeMethods::class, $this->hasMethod('publicMethod'));
        $this->assertThat(ClassWithSomeMethods::class, $this->hasMethod('public function publicMethod'));
        $this->assertThat(ClassWithSomeMethods::class, $this->hasMethod('!abstract !final public function publicMethod'));

        // ClassWithSomeMethods::abstractProtectedMethod
        $this->assertThat(ClassWithSomeMethods::class, $this->hasMethod('protected function abstractProtectedMethod'));
        $this->assertThat(ClassWithSomeMethods::class, $this->hasMethod('abstract function abstractProtectedMethod'));
        $this->assertThat(ClassWithSomeMethods::class, $this->hasMethod('abstract protected function abstractProtectedMethod'));


        $this->assertThat(ClassWithSomeMethods::class, $this->logicalNot($this->hasMethod('!abstract function abstractProtectedMethod')));
    }

    public function testHasMethodFailure(): void
    {
        $this->assertThat(ClassWithSomeMethods::class, $this->hasMethod('!abstract function abstractProtectedMethod'));
    }
}
