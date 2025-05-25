<?php

use Akashpoudelnp\NepaliDateCarbon\Services\ConverterEngine;

it('converts AD date to BS date correctly', function () {
    $converter = app(ConverterEngine::class);

    $result = $converter->convertToBS(1999, 06, 20);

    expect($result)->toBeArray()
        ->and($result['year'])->toBe(2056)
        ->and($result['month'])->toBe(3)
        ->and($result['day'])->toBe(6);
});

it('converts BS date to AD date correctly', function () {
    $converter = app(ConverterEngine::class);

    $result = $converter->convertToAD(2056, 3, 6);

    expect($result)->toBeArray()
        ->and($result['year'])->toBe(1999)
        ->and($result['month'])->toBe(6)
        ->and($result['day'])->toBe(20);
});
