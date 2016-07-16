<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet\Reader;

use Spreadsheet\{
    Reader\CsvReader,
    Reader\ReaderInterface,
    SpreadsheetInterface,
    Position
};
use Innmind\Filesystem\{
    File,
    Stream\StringStream,
    Directory
};

class CsvReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $reader = new CsvReader(',', false);

        $this->assertInstanceOf(ReaderInterface::class, $reader);
    }

    public function testReadSingleFile()
    {
        $file = new File(
            'sheet.csv',
            new StringStream(<<<CSV
A;B;C
a1;b1;c1
a2;b2;c2

CSV
            )
        );
        $reader = new CsvReader(';', true);

        $spreadsheet = $reader->read($file);

        $this->assertInstanceOf(SpreadsheetInterface::class, $spreadsheet);
        $this->assertCount(1, $spreadsheet->sheets());
        $this->assertSame('sheet', $spreadsheet->name());
        $sheet = $spreadsheet->get('sheet');
        $this->assertCount(3, $sheet->columns());
        $this->assertCount(2, $sheet->rows());
        $this->assertSame(
            'a1',
            $sheet->get(new Position('A', 1))->value()
        );
        $this->assertSame(
            'b1',
            $sheet->get(new Position('B', 1))->value()
        );
        $this->assertSame(
            'c1',
            $sheet->get(new Position('C', 1))->value()
        );
        $this->assertSame(
            'a2',
            $sheet->get(new Position('A', 2))->value()
        );
        $this->assertSame(
            'b2',
            $sheet->get(new Position('B', 2))->value()
        );
        $this->assertSame(
            'c2',
            $sheet->get(new Position('C', 2))->value()
        );
    }

    public function testReadWithHeader()
    {
        $file = new File(
            'sheet.csv',
            new StringStream(<<<CSV
A;B;C
a1;b1;c1
a2;b2;c2

CSV
            )
        );
        $reader = new CsvReader(';', false);

        $spreadsheet = $reader->read($file);

        $this->assertInstanceOf(SpreadsheetInterface::class, $spreadsheet);
        $this->assertCount(1, $spreadsheet->sheets());
        $this->assertSame('sheet', $spreadsheet->name());
        $sheet = $spreadsheet->get('sheet');
        $this->assertCount(3, $sheet->columns());
        $this->assertCount(3, $sheet->rows());
        $this->assertSame(
            'A',
            $sheet->get(new Position(0, 1))->value()
        );
        $this->assertSame(
            'B',
            $sheet->get(new Position(1, 1))->value()
        );
        $this->assertSame(
            'C',
            $sheet->get(new Position(2, 1))->value()
        );
        $this->assertSame(
            'a1',
            $sheet->get(new Position(0, 2))->value()
        );
        $this->assertSame(
            'b1',
            $sheet->get(new Position(1, 2))->value()
        );
        $this->assertSame(
            'c1',
            $sheet->get(new Position(2, 2))->value()
        );
        $this->assertSame(
            'a2',
            $sheet->get(new Position(0, 3))->value()
        );
        $this->assertSame(
            'b2',
            $sheet->get(new Position(1, 3))->value()
        );
        $this->assertSame(
            'c2',
            $sheet->get(new Position(2, 3))->value()
        );
    }

    public function testReadDirectory()
    {
        $reader = new CsvReader(';', true);
        $directory = (new Directory('spreadsheet'))
            ->add(
                new File(
                    'sheet1',
                    new StringStream(<<<CSV
A;B
a1;b1
CSV
                    )
                )
            )
            ->add(
                new File(
                    'sheet2',
                    new StringStream(<<<CSV
A;B
foo;bar
CSV
                    )
                )
            );

        $spreadsheet = $reader->read($directory);

        $this->assertInstanceOf(SpreadsheetInterface::class, $spreadsheet);
        $this->assertSame('spreadsheet', $spreadsheet->name());
        $this->assertCount(2, $spreadsheet->sheets());
        $sheet1 = $spreadsheet->get('sheet1');
        $this->assertCount(2, $sheet1->columns());
        $this->assertCount(1, $sheet1->rows());
        $this->assertSame(
            'a1',
            $sheet1->get(new Position('A', 1))->value()
        );
        $this->assertSame(
            'b1',
            $sheet1->get(new Position('B', 1))->value()
        );
        $sheet2 = $spreadsheet->get('sheet2');
        $this->assertCount(2, $sheet2->columns());
        $this->assertCount(1, $sheet2->rows());
        $this->assertSame(
            'foo',
            $sheet2->get(new Position('A', 1))->value()
        );
        $this->assertSame(
            'bar',
            $sheet2->get(new Position('B', 1))->value()
        );
    }

    /**
     * @expectedException Spreadsheet\Exception\NestedDirectoryReadNotSupportedException
     */
    public function testThrowWhenTryingToReadNestedDirectory()
    {
        (new CsvReader(',', false))->read(
            (new Directory('foo'))->add(
                new Directory('bar')
            )
        );
    }
}
