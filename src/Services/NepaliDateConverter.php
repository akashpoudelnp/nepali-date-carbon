<?php

namespace Akashpoudelnp\NepaliDateCarbon\Services;

use Carbon\Carbon;

class NepaliDateConverter
{
    public function __construct(public Carbon $datetime)
    {
    }

    public function convertToBS($translate = false): DateFormatter
    {
        return (new DateFormatter($this->datetime))->parseAndConvert($translate);
    }
}