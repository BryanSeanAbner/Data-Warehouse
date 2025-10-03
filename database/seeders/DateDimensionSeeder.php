<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Pharaonic\Hijri\HijriCarbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

Carbon::mixin(HijriCarbon::class); 

class DateDimensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $start = Carbon::create(2024, 1, 1);
        $end = Carbon::create(2025, 12, 31);

        $dates = [];

        while ($start->lte($end)) {
            $dateKey = $start->format('Ymd');
            $calendarQuarter = "Q" . (string)(intdiv($start->month - 1, 3) + 1);
            $dates[] = [
                'date_key' => (int)$dateKey,
                'date' => $start->toDateString(),
                'full_date_description' => $start->format('F j, Y'),
                'day_of_week' => $start->format('l'),
                'day_number_in_calendar_month' => $start->dayOfMonth,
                'day_number_in_calendar_year' => $start->dayOfYear,
                'day_number_in_fiscal_month' => $start->dayOfMonth,
                'day_number_in_fiscal_year' => $start->dayOfYear,
                'calendar_month_name' => $start->format('F'),
                'calendar_month_number_in_year' => $start->monthOfYear,
                'calendar_year_month' => $start->format('Y-m'),
                'calendar_quarter' => $calendarQuarter,
                'calendar_year_quarter' => $start->format('Y-') . $calendarQuarter,
                'calendar_year' => $start->year,
                'fiscal_month' => $start->format('F'),
                'fiscal_month_number_in_year' => $start->monthOfYear,
                'fiscal_year_month' => $start->format('Y-m'),
                'fiscal_quarter' => $calendarQuarter,
                'fiscal_year_quarter' => $start->format('Y-') . $calendarQuarter,
                'fiscal_year' => $start->year,
                'holiday_indicator' => match ($this->isHoliday($start)) {
                    true => "Holiday",
                    false => "Non-holiday"
                },
                'weekday_indicator' => match ($start->isWeekday()) {
                    true => "Weekday",
                    false => "Weekend",
                },
            ];
            $start->addDay();
        }

        DB::table('date_dimension')->insert($dates);

    }

    /**
     * Return true if the Carbon instance provided is considered a holiday and false otherwise.
     */
    private function isHoliday(Carbon $date): bool {
        $year = $date->year;
        $monthDay = (string)$date->format('m-d');

        $isFixedHoliday = match ($monthDay) {
            '01-01' => true, // New Year
            '05-01' => true, // Labor Day
            '06-01' => true, // Pancasila Day
            '08-17' => true, // Independence Day
            '12-25' => true, // Christmas
            default => false,
        };
        if ($isFixedHoliday) return true;

        $monthDayHijri = (string)$date->toHijri()->format('m-d');

        $isIslamicHoliday = match ($monthDayHijri) {
            '01-01' => true, // Islamic New Year
            '03-12' => true, // Prophet Muhammad's Birthday
            '07-27' => true, // Isra Mi'raj
            '10-01' => true, // Eid al-Fitr
            '12-10' => true, // Eid al-Adha
            default => false,
        };
        if ($isIslamicHoliday) return true;

        $easterSunday = Carbon::createFromTimestamp(
            easter_date($year),
            new \DateTimeZone('Asia/Jakarta'));
        Log:info($easterSunday);
        $goodFriday = $easterSunday->copy()->subDays(2); // 2 days before Easter
        $ascensionDay = $easterSunday->copy()->addDays(39); // 39 days after Easter
        if ($date->isSameDay($easterSunday)) return true;
        if ($date->isSameDay($goodFriday)) return true;
        if ($date->isSameDay($ascensionDay)) return true;
        
        // Unimplemented:
            // Chinese New Year
            // Balinese Day of Silence (Nyepi)
            // Vesak Day

        return false;
    }
}
