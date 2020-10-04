<?php declare(strict_types=1);

final class hasPregCapturesTest extends \PHPUnit\Framework\TestCase
{
    use \Tailors\PHPUnit\HasPregCapturesTrait;

    private $regexp;
    private $subject;
    private $matches;

    public function setUp(): void
    {
        $this->regexp  = '/(?<name>\w+) (?<surname>\w+)(?:, (?<age>\d+))?(?:, (?<city>\w+))?/';
        $this->subject = 'John Smith, London';
        preg_match($this->regexp, $this->subject, $this->matches, PREG_UNMATCHED_AS_NULL);
    }

    public function testHasPregCaptures(): void
    {
        // assert that:
        $this->assertThat($this->matches, $this->hasPregCaptures([
            'name'    => 'John',  // - name is 'John' (ok),
            'surname' => 'Smith', // - surname is 'Smith' (ok),
            'age'     => false,  // - age is absent (ok),
            'city'    => true,    // - city is present (ok).
        ]));
    }

    public function testHasPregCapturesFailure(): void
    {
        // assert that:
        $this->assertThat($this->matches, $this->hasPregCaptures([
            'name'    => 'John',  // - name is 'John' (ok),
            'surname' => 'Brown', // - surname is 'Brown' (fail),
            'age'     => true,   // - age is present (fail),
            'city'    => false,   // - city is absent (fail).
        ]));
    }
}
