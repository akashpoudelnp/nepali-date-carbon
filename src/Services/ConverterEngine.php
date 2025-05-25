<?php

namespace Akashpoudelnp\NepaliDateCarbon\Services;

use InvalidArgumentException;

class ConverterEngine
{
    /**
     * BS month names in English
     */
    public array $bsMonthsInEnglish = [
        1  => 'Baisakh',
        2  => 'Jestha',
        3  => 'Ashar',
        4  => 'Shrawan',
        5  => 'Bhadra',
        6  => 'Ashoj',
        7  => 'Kartik',
        8  => 'Manghir',
        9  => 'Poush',
        10 => 'Magh',
        11 => 'Falgun',
        12 => 'Chaitra',
    ];
    /**
     * BS month names in Nepali
     */
    public array $bsMonthsInNepali = [
        1  => 'वैशाख',
        2  => 'जेठ',
        3  => 'असार',
        4  => 'साउन',
        5  => 'भदौ',
        6  => 'असोज',
        7  => 'कार्तिक',
        8  => 'मंसिर',
        9  => 'पुष',
        10 => 'माघ',
        11 => 'फागुन',
        12 => 'चैत',
    ];
    /**
     * Day names in English
     */
    public array $daysInEnglish = [
        1 => 'Sunday',
        2 => 'Monday',
        3 => 'Tuesday',
        4 => 'Wednesday',
        5 => 'Thursday',
        6 => 'Friday',
        7 => 'Saturday',
    ];
    /**
     * Day names in Nepali
     */
    public array $daysInNepali = [
        1 => 'आइतवार',
        2 => 'सोमवार',
        3 => 'मङ्गलवार',
        4 => 'बुधवार',
        5 => 'बिहिवार',
        6 => 'शुक्रवार',
        7 => 'शनिवार',
    ];
    /**
     * Numbers in Nepali
     */
    public array $numbersInNepali = [
        0 => '०',
        1 => '१',
        2 => '२',
        3 => '३',
        4 => '४',
        5 => '५',
        6 => '६',
        7 => '७',
        8 => '८',
        9 => '९',
    ];
    /**
     * Nepali calendar data - key is [year - 2000], values are [year, month1, month2, ..., month12]
     * where month values represent days in that month
     */
    private array $calendarData;
    /**
     * English month days (normal year)
     */
    private array $normalMonthDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    /**
     * English month days (leap year)
     */
    private array $leapMonthDays = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    /**
     * Reference date for calculation
     * BS 2000/01/01 = 1943/04/14 AD
     */
    private array $referenceDate = [
        'bs' => ['year' => 2000, 'month' => 1, 'day' => 1],
        'ad' => ['year' => 1943, 'month' => 4, 'day' => 14],
    ];

    public function __construct(array $calendarData = [])
    {
        $this->calendarData = $calendarData ?: config('nepali-date-carbon.calendar_map');
    }

    /**
     * Convert AD date to BS date
     *
     * @param int $year AD year
     * @param int $month AD month (1-12)
     * @param int $day AD day
     * @return array BS date
     * @throws InvalidArgumentException If date is out of supported range
     */
    public function convertToBS(int $year, int $month, int $day): array
    {
        // Validate input
        if ($year < 1943 || $year > 2033) {
            throw new InvalidArgumentException("Year must be between 1943 and 2033");
        }

        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException("Month must be between 1 and 12");
        }

        $daysInMonth = $this->isLeapYear($year) ? $this->leapMonthDays[$month - 1] : $this->normalMonthDays[$month - 1];

        if ($day < 1 || $day > $daysInMonth) {
            throw new InvalidArgumentException("Invalid day for the given month and year");
        }

        // Calculate total days from reference date
        $totalDays = $this->getTotalDaysDifferenceAD(
            $this->referenceDate['ad']['year'],
            $this->referenceDate['ad']['month'],
            $this->referenceDate['ad']['day'],
            $year, $month, $day
        );

        // Calculate BS date from total days
        return $this->calculateBSDateFromDayDifference($totalDays);
    }

    /**
     * Check if the given year is a leap year
     */
    public function isLeapYear(int $year): bool
    {
        if ($year % 400 == 0) {
            return true;
        }
        if ($year % 100 == 0) {
            return false;
        }
        return ($year % 4 == 0);
    }

    /**
     * Calculate total days between two AD dates
     */
    private function getTotalDaysDifferenceAD(
        int $fromYear, int $fromMonth, int $fromDay,
        int $toYear, int $toMonth, int $toDay
    ): int
    {
        $days = 0;

        // Count days for years
        for ($year = $fromYear; $year < $toYear; $year++) {
            $days += $this->isLeapYear($year) ? 366 : 365;
        }

        // Count days up to from_month in from_year
        for ($month = 1; $month < $fromMonth; $month++) {
            $days -= $this->isLeapYear($fromYear) ?
                $this->leapMonthDays[$month - 1] :
                $this->normalMonthDays[$month - 1];
        }

        // Count days up to to_month in to_year
        for ($month = 1; $month < $toMonth; $month++) {
            $days += $this->isLeapYear($toYear) ?
                $this->leapMonthDays[$month - 1] :
                $this->normalMonthDays[$month - 1];
        }

        // Add/subtract remaining days
        $days -= $fromDay;
        $days += $toDay;

        return $days;
    }

    /**
     * Calculate BS date from days difference
     */
    private function calculateBSDateFromDayDifference(int $totalDays): array
    {
        $bsYear = $this->referenceDate['bs']['year'];
        $bsMonth = $this->referenceDate['bs']['month'];
        $bsDay = $this->referenceDate['bs']['day'];
        $dayOfWeek = 7; // Reference date is Saturday

        // Add days to the reference date
        while ($totalDays > 0) {
            $yearIndex = $bsYear - 2000;

            // If we've reached the end of the month, move to next month
            $daysInCurrentMonth = $this->calendarData[$yearIndex][$bsMonth];

            if ($bsDay == $daysInCurrentMonth) {
                $bsMonth++;
                $bsDay = 1;

                // If we've reached the end of the year, move to next year
                if ($bsMonth > 12) {
                    $bsYear++;
                    $bsMonth = 1;
                }
            } else {
                $bsDay++;
            }

            // Update day of week
            $dayOfWeek++;
            if ($dayOfWeek > 7) {
                $dayOfWeek = 1;
            }

            $totalDays--;
        }

        return [
            'year'        => $bsYear,
            'month'       => $bsMonth,
            'day'         => $bsDay,
            'day_of_week' => $dayOfWeek
        ];
    }

    /**
     * Convert BS date to AD date
     *
     * @param int $year BS year
     * @param int $month BS month (1-12)
     * @param int $day BS day
     * @return array AD date
     * @throws InvalidArgumentException If date is out of supported range
     */
    public function convertToAD(int $year, int $month, int $day): array
    {
        // Validate input
        if ($year < 2000 || $year > 2090) {
            throw new InvalidArgumentException("BS year must be between 2000 and 2090");
        }

        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException("Month must be between 1 and 12");
        }

        $yearIndex = $year - 2000;
        if (!isset($this->calendarData[$yearIndex][$month])) {
            throw new InvalidArgumentException("Invalid BS month for the given year");
        }

        $daysInMonth = $this->calendarData[$yearIndex][$month];
        if ($day < 1 || $day > $daysInMonth) {
            throw new InvalidArgumentException("Invalid BS day for the given month and year");
        }

        // Calculate total days from reference date to given BS date
        $totalDays = $this->getTotalDaysDifferenceBS(
            $this->referenceDate['bs']['year'],
            $this->referenceDate['bs']['month'],
            $this->referenceDate['bs']['day'],
            $year, $month, $day
        );

        // Calculate AD date from total days
        return $this->calculateADDateFromDayDifference($totalDays);
    }

    /**
     * Calculate total days between two BS dates
     */
    private function getTotalDaysDifferenceBS(
        int $fromYear, int $fromMonth, int $fromDay,
        int $toYear, int $toMonth, int $toDay
    ): int
    {
        $days = 0;

        // Count days for years
        for ($year = $fromYear; $year < $toYear; $year++) {
            $yearIndex = $year - 2000;
            for ($month = 1; $month <= 12; $month++) {
                $days += $this->calendarData[$yearIndex][$month];
            }
        }

        // Count days up to from_month in from_year
        $fromYearIndex = $fromYear - 2000;
        for ($month = 1; $month < $fromMonth; $month++) {
            $days -= $this->calendarData[$fromYearIndex][$month];
        }

        // Count days up to to_month in to_year
        $toYearIndex = $toYear - 2000;
        for ($month = 1; $month < $toMonth; $month++) {
            $days += $this->calendarData[$toYearIndex][$month];
        }

        // Add/subtract remaining days
        $days -= $fromDay;
        $days += $toDay;

        return $days;
    }

    /**
     * Calculate AD date from days difference
     */
    private function calculateADDateFromDayDifference(int $totalDays): array
    {
        $adYear = $this->referenceDate['ad']['year'];
        $adMonth = $this->referenceDate['ad']['month'];
        $adDay = $this->referenceDate['ad']['day'];
        $dayOfWeek = 7; // Reference date is Saturday

        // Add days to the reference date
        while ($totalDays > 0) {
            $daysInCurrentMonth = $this->isLeapYear($adYear) ?
                $this->leapMonthDays[$adMonth - 1] :
                $this->normalMonthDays[$adMonth - 1];

            if ($adDay == $daysInCurrentMonth) {
                $adMonth++;
                $adDay = 1;

                // If we've reached the end of the year, move to next year
                if ($adMonth > 12) {
                    $adYear++;
                    $adMonth = 1;
                }
            } else {
                $adDay++;
            }

            // Update day of week
            $dayOfWeek++;
            if ($dayOfWeek > 7) {
                $dayOfWeek = 1;
            }

            $totalDays--;
        }

        return [
            'year'        => $adYear,
            'month'       => $adMonth,
            'day'         => $adDay,
            'day_of_week' => $dayOfWeek
        ];
    }

    /**
     * Convert a number to Nepali numerals
     */
    public function getNumberInNepali($value): string
    {
        $numbers = str_split((string)$value);
        $result = '';

        foreach ($numbers as $number) {
            $result .= isset($this->numbersInNepali[$number]) ?
                $this->numbersInNepali[$number] : $number;
        }

        return $result;
    }
}
