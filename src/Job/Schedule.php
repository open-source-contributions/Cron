<?php
declare(strict_types = 1);

namespace Innmind\Cron\Job;

use Innmind\Cron\{
    Job\Schedule\Minutes,
    Job\Schedule\Hours,
    Job\Schedule\DaysOfMonth,
    Job\Schedule\Months,
    Job\Schedule\DaysOfWeek,
    Exception\DomainException
};
use Innmind\Immutable\Str;

final class Schedule
{
    private $minutes;
    private $hours;
    private $daysOfMonth;
    private $months;
    private $daysOfWeek;

    public function __construct(
        Minutes $minutes,
        Hours $hours,
        DaysOfMonth $daysOfMonth,
        Months $months,
        DaysOfWeek $daysOfWeek
    ) {
        $this->minutes = $minutes;
        $this->hours = $hours;
        $this->daysOfMonth = $daysOfMonth;
        $this->months = $months;
        $this->daysOfWeek = $daysOfWeek;
    }

    public static function of(string $value): self
    {
        $parts = Str::of($value)->split(' ');

        if ($parts->size() !== 5) {
            throw new DomainException($value);
        }

        try {
            return new self(
                Minutes::of((string) $parts->get(0)),
                Hours::of((string) $parts->get(1)),
                DaysOfMonth::of((string) $parts->get(2)),
                Months::of((string) $parts->get(3)),
                DaysOfWeek::of((string) $parts->get(4))
            );
        } catch (DomainException $e) {
            throw new DomainException($value);
        }
    }

    public static function everyMinute(): self
    {
        return self::of('* * * * *');
    }

    public static function everyHourAt(int $minute): self
    {
        return self::of("$minute * * * *");
    }

    public static function everyDayAt(int $hour, int $minute): self
    {
        return self::of("$minute $hour * * *");
    }

    public static function everyMondayAt(int $hour, int $minute): self
    {
        return self::of("$minute $hour * * 0");
    }

    public static function everyTuesdayAt(int $hour, int $minute): self
    {
        return self::of("$minute $hour * * 1");
    }

    public static function everyWednesdayAt(int $hour, int $minute): self
    {
        return self::of("$minute $hour * * 2");
    }

    public static function everyThursdayAt(int $hour, int $minute): self
    {
        return self::of("$minute $hour * * 3");
    }

    public static function everyFridayAt(int $hour, int $minute): self
    {
        return self::of("$minute $hour * * 4");
    }

    public static function everySaturdayAt(int $hour, int $minute): self
    {
        return self::of("$minute $hour * * 5");
    }

    public static function everySundayAt(int $hour, int $minute): self
    {
        return self::of("$minute $hour * * 6");
    }

    public function __toString(): string
    {
        return "{$this->minutes} {$this->hours} {$this->daysOfMonth} {$this->months} {$this->daysOfWeek}";
    }
}