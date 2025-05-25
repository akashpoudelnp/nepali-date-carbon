<?php

namespace Akashpoudelnp\NepaliDateCarbon;

use Akashpoudelnp\NepaliDateCarbon\Services\ConverterEngine;
use Akashpoudelnp\NepaliDateCarbon\Services\NepaliDate;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

final class NepaliDateCarbonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/nepali-date-carbon.php',
            'nepali-date-carbon'
        );
    }

    public function boot(): void
    {
        $this->setupMacros();

        $this->publishes([
            __DIR__ . '/../config/nepali-date-carbon.php' => config_path('nepali-date-carbon.php'),
        ]);
    }

    private function setupMacros(): void
    {
        // Convert current Carbon date to BS
        Carbon::macro('convertToBS', function (string $format = 'Y-m-d', bool $inNepali = false) {
            $nepaliDate = new NepaliDate($this);
            return $nepaliDate->format($format, $inNepali);
        });

        // Get BS date as array
        Carbon::macro('toBSArray', function () {
            $nepaliDate = new NepaliDate($this);
            return $nepaliDate->toBSArray();
        });

        // Get NepaliDate instance from Carbon
        Carbon::macro('toNepaliDate', function () {
            return new NepaliDate($this);
        });

        // Static method to create Carbon from BS date
        Carbon::macro('createFromBS', function (int $year, int $month, int $day, int $hour = 0, int $minute = 0, int $second = 0, int $microsecond = 0) {
            $converter = new ConverterEngine();
            $adDate = $converter->convertToAD($year, $month, $day);
            return Carbon::createFromFormat(
                'Y-m-d H:i:s.u',
                sprintf('%04d-%02d-%02d %02d:%02d:%02d.%06d', $adDate['year'], $adDate['month'], $adDate['day'], $hour, $minute, $second, $microsecond),
            );
        });
    }
}
