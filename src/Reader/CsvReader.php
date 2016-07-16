<?php
declare(strict_types = 1);

namespace Spreadsheet\Reader;

use Spreadsheet\{
    SpreadsheetInterface,
    Spreadsheet,
    Exception\NestedDirectoryReadNotSupportedException,
    Exception\InvalidArgumentException,
    SheetInterface,
    Sheet,
    Cell,
    Position
};
use Innmind\Filesystem\{
    FileInterface,
    DirectoryInterface
};

final class CsvReader implements ReaderInterface
{
    private $delimiter;
    private $useFirstLineAsColumnIdentifier;

    public function __construct(
        string $delimiter,
        bool $useFirstLineAsColumnIdentifier
    ) {
        if (empty($delimiter)) {
            throw new InvalidArgumentException;
        }

        $this->delimiter = $delimiter;
        $this->useFirstLineAsColumnIdentifier = $useFirstLineAsColumnIdentifier;
    }

    public function read(FileInterface $file): SpreadsheetInterface
    {
        $spreadsheet = new Spreadsheet(
            basename((string) $file->name(), '.csv')
        );

        if ($file instanceof DirectoryInterface) {
            foreach ($file as $subFile) {
                if ($subFile instanceof DirectoryInterface) {
                    throw new NestedDirectoryReadNotSupportedException;
                }

                $spreadsheet = $spreadsheet->add(
                    $this->buildSheet($subFile)
                );
            }
        } else {
            $spreadsheet = $spreadsheet->add(
                $this->buildSheet($file)
            );
        }

        return $spreadsheet;
    }

    private function buildSheet(FileInterface $file): SheetInterface
    {
        $csv = tmpfile();
        $stream = $file->content()->rewind();

        while (!$stream->isEof()) {
            fwrite($csv, $stream->read(8192));
        }

        rewind($csv);
        $sheet = new Sheet(
            basename((string) $file->name(), '.csv')
        );

        $position = 1;
        $firstLine = fgetcsv($csv, 0, $this->delimiter);

        if (!$this->useFirstLineAsColumnIdentifier) {
            rewind($csv);
        }

        while (($line = fgetcsv($csv, 0, $this->delimiter)) !== false) {
            foreach ($line as $key => $value) {
                $sheet = $sheet->add(
                    new Cell(
                        new Position(
                            $this->useFirstLineAsColumnIdentifier ?
                                $firstLine[$key] : $key,
                            $position
                        ),
                        $value
                    )
                );
            }

            ++$position;
        }

        fclose($csv);

        return $sheet;
    }
}
