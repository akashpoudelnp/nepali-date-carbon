<p align="center">
  <img src="https://akashpoudel.com.np/name.svg" alt="Aakash Logo" width="250">
</p>

<h1 align="center">Nepali Date for Carbon</h1>

<p align="center">
  <a href="https://packagist.org/packages/akashpoudelnp/nepali-date-carbon"><img src="https://img.shields.io/packagist/v/akashpoudelnp/nepali-date-carbon" alt="Latest Version"></a>
  <a href="https://github.com/akashpoudelnp/nepali-date-carbon/actions"><img src="https://github.com/akashpoudelnp/nepali-date-carbon/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/akashpoudelnp/nepali-date-carbon"><img src="https://img.shields.io/packagist/dt/akashpoudelnp/nepali-date-carbon" alt="Total Downloads"></a>
  <a href="https://github.com/akashpoudelnp/nepali-date-carbon/blob/master/LICENSE.md"><img src="https://img.shields.io/packagist/l/akashpoudelnp/nepali-date-carbon" alt="License"></a>
</p>

## Introduction

This is a powerful package that extends Laravel's Carbon to support Nepali (Bikram Sambat) dates. It provides an elegant
way to convert between Gregorian (AD) and Nepali (BS) calendar systems, format dates in Nepali, and display dates in
Nepali language.

## Features

- 🔄 Seamless conversion between AD and BS dates
- 🔢 Support for Nepali date formats
- 🇳🇵 Nepali language translation for dates
- 🧩 Extends Carbon - use all existing Carbon functionality
- ⚡ Easy to use with Laravel's existing date handling

## Requirements

- PHP 8.2+
- Laravel 12+

## Installation

Install the package via Composer:

```bash
composer require akashpoudelnp/nepali-date-carbon
```

## Documentation

### Basic Usage

#### Converting AD to BS

You can convert any Carbon instance to a Nepali Date:

```php
use Carbon\Carbon;

// Convert current date to BS
$nepaliDate = now()->convertToBS();
echo $nepaliDate->format('Y-m-d'); // "2080-12-15"

// Convert a specific date to BS
$date = Carbon::parse('2023-05-15');
$nepaliDate = $date->convertToBS();
echo $nepaliDate->format('Y F d'); // "2080 Jestha 01"
```

#### Converting BS to AD

You can also convert a Nepali Date back to a Gregorian Date:

```php
use Carbon\Carbon;

// Convert current BS date to AD
$gregorianDate = now()->createFromBS($year, $month, $day);
echo $gregorianDate->format('Y-m-d'); // "2023-05-15"
```

#### Formatting Nepali Dates

You can format Nepali dates using the `format` method:

```php
$nepaliDate = now()->convertToBS();
echo $nepaliDate->format('Y F d'); // "2080 Jestha 01"
echo $nepaliDate->format('l, F j, Y'); // "Saturday, Jestha 01, 2080"
```

### Date Formatting

The format method supports standard Carbon date formatting options, but with Nepali date formats. Here are some common
format characters you can use:

- `Y`: Year (4 digits)
- `F`: Full month name
- `d`: Day of the month (2 digits)
- `l`: Full name of the day of the week
- `j`: Day of the month without leading zeros
- `D`: Short day name
- `m`: Month number (2 digits)
- `n`: Month number without leading zeros
- `g`: Hour in 12-hour format without leading zeros
- `G`: Hour in 24-hour format without leading zeros
- `h`: Hour in 12-hour format with leading zeros
- `H`: Hour in 24-hour format with leading zeros
- `i`: Minutes with leading zeros
- `s`: Seconds with leading zeros

### Localization

The package supports Nepali language localization. You can pass the `$isNepali` parameter to the `format` method or
`convertToBS`
to get the date in Nepali language:

```php
$nepaliDate = now()->convertToBS(
    format: 'Y-m-d',
    inNepali: true // Or use can use app()->isLocale('ne') to check if the app locale is Nepali
);
echo $nepaliDate; // "२०८०-१२-१५"
```

### Testing

To run the tests, use the following command:

```bash
composer test
```

## Contributing

We welcome contributions to this package! Please read
our [contributing guidelines](https://github.com/akashpoudelnp/nepali-date-carbon/blob/master/CONTRIBUTING.md) for
details on how to get started.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/license/mit/).

## Credits

- [Aakash Poudel](https://akashpoudel.com.np) - Package Author
- [Laravel](https://laravel.com) - The framework this package extends
- [Carbon](https://carbon.nesbot.com) - The date library this package extends
- [Contributors](https://github.com/akashpoudelnp/nepali-date-carbon/graphs/contributors) - All the amazing people who
  have contributed to this project
