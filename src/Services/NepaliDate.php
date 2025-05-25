<?php

namespace Akashpoudelnp\NepaliDateCarbon\Services;

use Carbon\Carbon;
use InvalidArgumentException;

class NepaliDate
{
    private ConverterEngine $converter;
    private array $bsDate;
    private Carbon $adDate;

    /**
     * Create a new NepaliDate instance
     *
     * @param array|Carbon|null $date Date to initialize with (Carbon, BS array, or null for current date)
     */
    public function __construct($date = null)
    {
        $this->converter = new ConverterEngine();

        if ($date === null) {
            $this->adDate = Carbon::now();
            $this->bsDate = $this->converter->convertToBS(
                $this->adDate->year,
                $this->adDate->month,
                $this->adDate->day
            );
        } elseif ($date instanceof Carbon) {
            $this->adDate = $date;
            $this->bsDate = $this->converter->convertToBS(
                $date->year,
                $date->month,
                $date->day
            );
        } elseif (is_array($date) && isset($date['year'], $date['month'], $date['day'])) {
            $this->bsDate = [
                'year'        => $date['year'],
                'month'       => $date['month'],
                'day'         => $date['day'],
                'day_of_week' => $date['day_of_week'] ?? -1
            ];

            $adDate = $this->converter->convertToAD($date['year'], $date['month'], $date['day']);
            $this->adDate = Carbon::create($adDate['year'], $adDate['month'], $adDate['day']);

            // If day of week was not provided, set it now
            if (!isset($date['day_of_week']) || $date['day_of_week'] == -1) {
                $this->bsDate['day_of_week'] = $adDate['day_of_week'];
            }
        } else {
            throw new InvalidArgumentException("Invalid date provided");
        }
    }

    /**
     * Create a new NepaliDate instance from BS date
     */
    public static function createFromBS(int $year, int $month, int $day): self
    {
        return new self(['year' => $year, 'month' => $month, 'day' => $day]);
    }

    /**
     * Get Carbon instance of the AD date
     */
    public function toCarbon(): Carbon
    {
        return $this->adDate;
    }

    /**
     * Get BS date as an array
     */
    public function toBSArray(): array
    {
        return $this->bsDate;
    }

    /**
     * Format the BS date
     *
     * @param string $format Format string (using PHP date format characters)
     * @param bool $inNepali Whether to format in Nepali language
     */
    public function format(string $format = 'Y-m-d', bool $inNepali = false): string
    {
        $result = '';
        $length = strlen($format);

        for ($i = 0; $i < $length; $i++) {
            $char = $format[$i];

            switch ($char) {
                case 'Y': // Year (4 digits)
                    $value = $this->bsDate['year'];
                    $result .= $inNepali ? $this->converter->getNumberInNepali($value) : $value;
                    break;

                case 'y': // Year (2 digits)
                    $value = substr($this->bsDate['year'], -2);
                    $result .= $inNepali ? $this->converter->getNumberInNepali($value) : $value;
                    break;

                case 'm': // Month (2 digits)
                    $value = str_pad($this->bsDate['month'], 2, '0', STR_PAD_LEFT);
                    $result .= $inNepali ? $this->converter->getNumberInNepali($value) : $value;
                    break;

                case 'n': // Month (no leading zeros)
                    $value = $this->bsDate['month'];
                    $result .= $inNepali ? $this->converter->getNumberInNepali($value) : $value;
                    break;

                case 'F': // Month name
                    $monthNames = $inNepali ?
                        $this->converter->bsMonthsInNepali :
                        $this->converter->bsMonthsInEnglish;
                    $result .= $monthNames[$this->bsDate['month']];
                    break;

                case 'd': // Day (2 digits)
                    $value = str_pad($this->bsDate['day'], 2, '0', STR_PAD_LEFT);
                    $result .= $inNepali ? $this->converter->getNumberInNepali($value) : $value;
                    break;

                case 'j': // Day (no leading zeros)
                    $value = $this->bsDate['day'];
                    $result .= $inNepali ? $this->converter->getNumberInNepali($value) : $value;
                    break;

                case 'D':
                case 'l': // Day of week
                    $dayNames = $inNepali ?
                        $this->converter->daysInNepali :
                        $this->converter->daysInEnglish;
                    $result .= $dayNames[$this->bsDate['day_of_week']];
                    break;

                default:
                    // For time formats, pass through to Carbon
                    if (in_array($char, ['a', 'A', 'g', 'G', 'h', 'H', 'i', 's'])) {
                        $result .= $this->adDate->format($char);
                        if ($inNepali && is_numeric($this->adDate->format($char))) {
                            $result = $this->converter->getNumberInNepali($result);
                        }
                    } else {
                        $result .= $char;
                    }
            }
        }

        return $result;
    }
}
