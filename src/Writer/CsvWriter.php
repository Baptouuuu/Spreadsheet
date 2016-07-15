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

        if ($this->withHeader) {
            fputcsv($csv, $sheet->columns()->keys()->toPrimitive(), $this->delimiter);
        }

        $sheet
            ->rows()
            ->reduce(
                $csv,
                function($carry, $identifier, RowInterface $row) {
                    fputcsv(
                        $carry,
                        $row->cells()->reduce(
                            [],
                            function(array $carry, $column, CellInterface $cell): array {
                                $carry[] = $cell->value();

                                return $carry;
                            }
                        ),
                        $this->delimiter
                    );

                    return $carry;
                }
            );

        return new Csv($sheet->name(), new Stream($csv));
    }
}
