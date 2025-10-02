<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                'holiday_indicator' => 'Non-Holiday',
                'weekday_indicator' => match ($start->isWeekday()) {
                    true => "Weekday",
                    false => "Weekend",
                },
            ];
            $start->addDay();
        }

        DB::table('date_dimension')->insert($dates);

    }
}
