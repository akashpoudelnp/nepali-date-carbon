<?php

namespace Akashpoudelnp\NepaliDateCarbon\Services;

use Carbon\Carbon;

class DateFormatter
{
    public array $formatted;
    public ?string $format = null;
    private bool $translationEnabled = false;
    private string|array|null $convertedDateTime;
    private array $formatElements;
    private ConverterEngine $converter;
    private array $baseFormat;
    private array $separators;

    public function __construct(public Carbon $datetime)
    {
        $this->converter = (new ConverterEngine())->parseFromCarbon($datetime);
    }

    public function baseDateFormat()
    {
        return $this->format('d F, Y h:i a');
    }

    public function format($format = null)
    {
        if (!$format) {
            return $this->convertedDateTime;
        }

        $this->format = $format;

        $this->parseAndConvert($this->translationEnabled);

        return $this->convertedDateTime;
    }

    public function parseAndConvert($translate)
    {
        if ($translate === true) {
            $this->translationEnabled = true;
        }

        if (!$this->format) {
            $this->format = 'd F, Y h:i:a';
        }

        $this->formatted      = [];
        $this->baseFormat     = str_split($this->format);
        $this->formatElements = array_values(array_diff($this->baseFormat, [
            '/', ' ', ':', '-', ',',
        ]));
        $this->separators     = array_values(array_diff([
            '/', ' ', ':', '-', ',',
        ], $this->formatElements));

        foreach ($this->formatElements as $dateFormat) {
            switch ($dateFormat) {
                case 'd':
                    $this->formatted['d'] = $this->converter->nepaliDay;
                    break;
                case 'm':
                    $this->formatted['m'] = $this->converter->nepaliMonth;
                    break;
                case 'F':
                    $this->formatted['F'] = $this->converter->formattedBSMonth($this->converter->nepaliMonth);
                    break;
                case 'Y':
                    $this->formatted['Y'] = $this->getFormattedYear();
                    break;
                case 'y':
                    $this->formatted['y'] = substr($this->getFormattedYear(), -2);
                    break;
                case 'h':
                    $this->formatted['h'] = $this->datetime->format('h');
                    break;
                case 'i':
                    $this->formatted['i'] = $this->datetime->format('i');
                    break;
                case 'a':
                    $this->formatted['a'] = $this->datetime->format('a');
                    break;
                case 'A':
                    $this->formatted['A'] = $this->datetime->format('A');
                    break;
            }
        }

        $this->convertedDateTime = $this->format;

        $this->compileDateTimeWithSeparators();

        return $this;
    }

    private function getFormattedYear()
    {
        return $this->converter->nepaliYear;
    }

    public function compileDateTimeWithSeparators()
    {
        $this->convertedDateTime = '';

        if ($this->translationEnabled) {
            foreach ($this->formatElements as $format) {
                if ($format === 'd' || $format === 'Y' ||
                    $format === 'y' || $format === 'm' ||
                    $format === 'h' || $format === 'i' ||
                    $format === 'H'
                ) {
                    $this->formatted[$format] = $this->converter->formattedNepaliNumber($this->formatted[$format]);
                }

                if ($format === 'F') {
                    $this->formatted[$format] = $this->converter->formattedNepaliMonth($this->converter->nepaliMonth);
                }
            }
        }

        foreach ($this->baseFormat as $baseFormat) {
            if (in_array($baseFormat, $this->separators, true)) {
                $this->convertedDateTime .= $baseFormat;
            } else {
                $this->convertedDateTime .= $this->formatted[$baseFormat];
            }
        }
    }
}