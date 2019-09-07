<?php
declare(strict_types = 1);

namespace Tests\Innmind\Cron\Job\Schedule;

use Innmind\Cron\{
    Job\Schedule\Months,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait,
};

class MonthsTest extends TestCase
{
    use TestTrait;

    public function testEachMonth()
    {
        $schedule = Months::each();

        $this->assertInstanceOf(Months::class, $schedule);
        $this->assertSame('*', (string) $schedule);
    }

    public function testEachMonthFromRawString()
    {
        $schedule = Months::of('*');

        $this->assertInstanceOf(Months::class, $schedule);
        $this->assertSame('*', (string) $schedule);
    }

    public function testPreciseMonthFromRawString()
    {
        $this
            ->forAll(Generator\elements(...range(1, 12)))
            ->then(function($minute) {
                $schedule = Months::of((string) $minute);

                $this->assertInstanceOf(Months::class, $schedule);
                $this->assertSame((string) $minute, (string) $schedule);
            });
    }

    public function testListOfMonthsFromRawString()
    {
        $this
            ->forAll(
                Generator\elements(...range(1, 12)),
                Generator\elements(...range(1, 12))
            )
            ->then(function($minute, $occurences) {
                $list = implode(
                    ',',
                    array_pad([], $occurences, $minute)
                );

                $schedule = Months::of($list);

                $this->assertInstanceOf(Months::class, $schedule);
                $this->assertSame($list, (string) $schedule);
            });
    }

    public function testRangeOfMonthsFromRawString()
    {
        $this
            ->forAll(
                Generator\elements(...range(1, 12)),
                Generator\elements(...range(1, 12))
            )
            ->then(function($from, $to) {
                $schedule = Months::of("$from-$to");

                $this->assertInstanceOf(Months::class, $schedule);
                $this->assertSame("$from-$to", (string) $schedule);
            });
    }

    public function testEachMonthSteppedFromRawString()
    {
        $this
            ->forAll(Generator\elements(...range(1, 12)))
            ->then(function($step) {
                $schedule = Months::of("*/$step");

                $this->assertInstanceOf(Months::class, $schedule);
                $this->assertSame("*/$step", (string) $schedule);
            });
    }

    public function testRangeOfMonthsSteppedFromRawString()
    {
        $this
            ->forAll(
                Generator\elements(...range(1, 12)),
                Generator\elements(...range(1, 12)),
                Generator\elements(...range(1, 12))
            )
            ->then(function($from, $to, $step) {
                $schedule = Months::of("$from-$to/$step");

                $this->assertInstanceOf(Months::class, $schedule);
                $this->assertSame("$from-$to/$step", (string) $schedule);
            });
    }

    public function testThrowExceptionWhenUsingRandomString()
    {
        $this
            ->forAll(Generator\string())
            ->then(function($value) {
                $this->expectException(DomainException::class);
                $this->expectExceptionMessage($value);

                Months::of($value);
            });
    }
}
