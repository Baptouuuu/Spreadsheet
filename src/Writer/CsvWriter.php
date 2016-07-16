<?php
declare(strict_types = 1);

namespace Spreadsheet\Writer;

use Spreadsheet\{
    SpreadsheetInterface,
    Exception\InvalidArgumentException,
    SheetInterface,
    RowInterface,
    CellInterface,
    File\Csv
};
use Innmind\Filesystem\{
    FileInterface,
    Directory,
    Stream\Stream
};
use Innmind\Immutable\MapInterface;

final class CsvWriter implements WriterInterface
{
    private $delimiter;
    private $withHeader;

    public function __construct(string $delimiter, bool $withHeader)
    {
        if (empty($delimiter)) {
            throw new InvalidArgumentException;
        }

        $this->delimiter = $delimiter;
        $this->withHeader = $withHeader;
    }

    public function write(SpreadsheetInterface $spreadsheet): FileInterface
    {
        $directory = $spreadsheet
            ->sheets()
            ->reduce(
                new Directory($spreadsheet->name()),
                function(Directory $carry, string $name, SheetInterface $sheet): Directory {
                    return $carry->add($this->buildFile($sheet));
                }
            );

        if ($directory->count() === 1) {
            return $directory->get(
                $spreadsheet->sheets()->values()->first()->name().'.csv'
            );
        }

        return $directory;
    }

    private function buildFile(SheetInterface $sheet): FileInterface
    {
        $csv = fopen('php://temp', 'r+');
        $columns = $sheet
            ->columns()
            ->keys()
            ->sort(function($a, $b): bool {
                return $a > $b;
            })
            ->toPrimitive();
        $default = array_fill_keys(array_values($columns), '');

        if ($this->withHeader) {
            fputcsv($csv, $columns, $this->delimiter);
        }

        $sheet
            ->rows()
            ->values()
            ->sort(function(RowInterface $a, RowInterface $b): bool {
                return $a->identifier() > $b->identifier();
            })
            ->reduce(
                $csv,
                function($carry, RowInterface $row) use ($default) {
                    fputcsv(
                        $carry,
                        $this->fill($default, $row->cells()),
                        $this->delimiter
                    );

                    return $carry;
                }
            );

        return new Csv($sheet->name(), new Stream($csv));
    }

    private function fill(array $default, MapInterface $cells): array
    {
        $line = $cells->reduce(
            $default,
            function(array $carry, $column, CellInterface $cell): array {
                $carry[$column] = (string) $cell;

                return $carry;
            }
        );

        return array_values($line);
    }
}
