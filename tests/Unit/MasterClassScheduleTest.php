<?php

namespace Tests\Unit;

use App\Support\MasterClassSchedule;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class MasterClassScheduleTest extends TestCase
{
    public function test_it_exposes_known_time_slots(): void
    {
        $this->assertSame([
            '09:00' => '09:00 - 11:00',
            '11:00' => '11:00 - 13:00',
            '13:00' => '13:00 - 15:00',
            '15:00' => '15:00 - 17:00',
        ], MasterClassSchedule::all());
    }

    public function test_it_normalizes_and_labels_slots(): void
    {
        $this->assertSame('09:00', MasterClassSchedule::normalize('09:00:00'));
        $this->assertSame('09:00 - 11:00', MasterClassSchedule::label('09:00:00'));
        $this->assertSame('17:30', MasterClassSchedule::label('17:30'));
    }

    public function test_it_validates_slots(): void
    {
        $this->assertTrue(MasterClassSchedule::isValid('11:00:00'));
        $this->assertFalse(MasterClassSchedule::isValid(null));
        $this->assertFalse(MasterClassSchedule::isValid('20:00'));
    }

    public function test_it_builds_date_options_from_today(): void
    {
        CarbonImmutable::setTestNow('2026-05-16');

        $dates = MasterClassSchedule::dateOptions(3);

        $this->assertCount(3, $dates);
        $this->assertSame('2026-05-16', $dates[0]->format('Y-m-d'));
        $this->assertSame('2026-05-18', $dates[2]->format('Y-m-d'));

        CarbonImmutable::setTestNow();
    }
}
