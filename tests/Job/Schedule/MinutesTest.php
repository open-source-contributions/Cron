<?php
declare(strict_types = 1);

namespace Tests\Innmind\Cron\Job\Schedule;

use Innmind\Cron\{
    Job\Schedule\Minutes,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait,
};

class MinutesTest extends TestCase
{
    use TestTrait;

    public function testEachMinute()
    {
        $schedule = Minutes::each();

        $this->assertInstanceOf(Minutes::class, $schedule);
        $this->assertSame('*', $schedule->toString());
    }

    public function testEachMinuteFromRawString()
    {
        $schedule = Minutes::of('*');

        $this->assertInstanceOf(Minutes::class, $schedule);
        $this->assertSame('*', $schedule->toString());
    }

    public function testPreciseMinuteFromRawString()
    {
        $this
            ->forAll(Generator\elements(...range(0, 59)))
            ->then(function($minute) {
                $schedule = Minutes::of((string) $minute);

                $this->assertInstanceOf(Minutes::class, $schedule);
                $this->assertSame((string) $minute, $schedule->toString());
            });
    }

    public function testListOfMinutesFromRawString()
    {
        $this
            ->forAll(
                Generator\elements(...range(0, 59)),
                Generator\elements(...range(1, 59))
            )
            ->then(function($minute, $occurences) {
                $list = implode(
                    ',',
                    array_pad([], $occurences, $minute)
                );

                $schedule = Minutes::of($list);

                $this->assertInstanceOf(Minutes::class, $schedule);
                $this->assertSame($list, $schedule->toString());
            });
    }

    public function testRangeOfMinutesFromRawString()
    {
        $this
            ->forAll(
                Generator\elements(...range(0, 59)),
                Generator\elements(...range(0, 59))
            )
            ->then(function($from, $to) {
                $schedule = Minutes::of("$from-$to");

                $this->assertInstanceOf(Minutes::class, $schedule);
                $this->assertSame("$from-$to", $schedule->toString());
            });
    }

    public function testEachMinuteSteppedFromRawString()
    {
        $this
            ->forAll(Generator\elements(...range(0, 59)))
            ->then(function($step) {
                $schedule = Minutes::of("*/$step");

                $this->assertInstanceOf(Minutes::class, $schedule);
                $this->assertSame("*/$step", $schedule->toString());
            });
    }

    public function testRangeOfMinutesSteppedFromRawString()
    {
        $this
            ->forAll(
                Generator\elements(...range(0, 59)),
                Generator\elements(...range(0, 59)),
                Generator\elements(...range(0, 59))
            )
            ->then(function($from, $to, $step) {
                $schedule = Minutes::of("$from-$to/$step");

                $this->assertInstanceOf(Minutes::class, $schedule);
                $this->assertSame("$from-$to/$step", $schedule->toString());
            });
    }

    public function testThrowExceptionWhenUsingRandomString()
    {
        $this
            ->forAll(Generator\string())
            ->then(function($value) {
                $this->expectException(DomainException::class);
                $this->expectExceptionMessage($value);

                Minutes::of($value);
            });
    }
}
