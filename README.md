# Nepali Date Using Carbon

<img alt="Packagist Version" src="https://img.shields.io/packagist/v/akashpoudelnp/nepali-date-carbon">

Nepali Carbon is a Laravel package extension that adds support for Nepali date time in the Carbon package of Laravel.
With this package, you can easily convert dates and times between the Gregorian calendar used by Carbon and the Nepali
calendar.

## Installation

You can install via Composer using the following command:

```
$ composer require akashpoudelnp/nepali-date-carbon
 ```

```json
{
  "require": {
    "akashpoudelnp/nepali-date-carbon": "^2.16"
  }
}
```

## Usage

Once you have installed and configured the package, you can use it just like you would use Carbon in Laravel. Here are
some examples of how to use this package:

### Converting Converting a Gregorian date to a Nepali date

```php
$date = now(); // 20 February, 2023 01:09 pm
$convertedDate = $date->convertToBS()->format();
// Output: 8 Falgun, 2079 01:09:pm
```

This package extends the functionality of carbon by allowing you to use the **carbon** instance rather than importing
and using a class.

### Formats

This package allows you to use the standard date time format to format the given date time.

```php
// Assume the current nepali date is Falgun 08, 2079
    now()->convertToBS()->format('d') // 8
    now()->convertToBS()->format('m') // 11
    now()->convertToBS()->format('F') // Falgun
    now()->convertToBS()->format('Y') // 2079
    now()->convertToBS()->format('y') // 79
```

You can use any of these combinations to change to your desired format.

### Translation

We have added a functionality to translate the date time to nepali language.
The ``convertToBS(translate)`` takes a boolean argument which will result in the
converted date time to be translated.

```php
// ८ फागुन, २०७९ ०१:२१:pm
now()->convertToBS(translate: true)->format('d F, Y h:i a') 
```

## Credits

Nepali Carbon uses the [nesbot/carbon](https://carbon.nesbot.com/) as the base. 
