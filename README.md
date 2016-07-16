# Spreadsheet

| `master` | `develop` |
|----------|-----------|
| [[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/?branch=master) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/?branch=develop) |
| [![Code Coverage](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/?branch=develop) |
| [![Build Status](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/build-status/master) | [![Build Status](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/Baptouuuu/Spreadsheet/build-status/develop) |

Library providing an object modeling of a spreadsheet (all objects are immutable). It comes with objects to build a spreadsheet out of a file and dump a spreadsheet into a file.

## Installation

```sh
composer require baptouuuu/spreadsheet
```

## Usage

```php
use Spreadsheet\{
    Spreadsheet,
    Sheet,
    Cell,
    Position
};

$sheet = new Sheet('sheet 1');
$sheet = $sheet
    ->add(
        new Cell(
            new Position('A', 1),
            'A1 value'
        )
    )
    ->add(
        new Cell(
            new Position('B', 2),
            'B2 value'
        )
    );
$spreadsheet = new Spreadsheet('My Spreadsheet');
$spreadsheet = $spreadsheet->add($sheet);
```

## Transform a spreadsheet to a CSV

```php
use Spreadsheet\{
    Writer\CsvWriter,
    Formatter\FormatterInterface,
    File\Csv
};
use Innmind\Immutable\Map;

$writer = new CsvWriter(';', true, new Map('string', FormatterInterface::class));
$file = $writer->write($spreadsheet);
$file instanceof Csv; //true
(string) $file->name(); //sheet 1.csv
(string) $file->content();
/*
A;B
A1;
;B2
*/
```

In case your spreadsheet contains more than one sheet, the writer will return an instance of [`DirectoryInterface`](https://github.com/Innmind/Filesystem/blob/develop/DirectoryInterface.php).

## Building a spreadsheet from a CSV

```php
use Spreadsheet\{
    Reader\CsvReader,
    File\Csv,
    Position
};
use Innmind\Filesystem\Stream\StringStream;

$reader = new CsvReader(';', true);
$spreadsheet = $reader->read(
    new Csv(
        'sheet.csv',
        new StringStream(<<<CSV
A;B
A1;B1
CSV
        )
    )
);

$spreadsheet->name(); //sheet
$spreadsheet->get('sheet')->name(); //sheet
$spreadsheet->get('sheet')->get(new Position('A', 1))->value(); //A1
$spreadsheet->get('sheet')->get(new Position('B', 1))->value(); //B1
```

If you give an instance of [`DirectoryInterface`](https://github.com/Innmind/Filesystem/blob/develop/DirectoryInterface.php) to the reader, each file will be a sheet in your spreadhseet.
