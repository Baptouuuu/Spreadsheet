<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet\Writer;

use Spreadsheet\{
    Writer\CsvWriter,
    Writer\WriterInterface,
    Spreadsheet,
    Sheet,
    Cell,
    Position,
    File\Csv,
    Formatter\FormatterInterface,
    Formatter\DateFormatter,
    Cell\DateCell
};
use Innmind\Filesystem\DirectoryInterface;
use Innmind\Immutable\Map;

class CsvWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $writer = new CsvWriter(
            ';',
            true,
            new Map('string', FormatterInterface::class)
        );

        $this->assertInstanceOf(WriterInterface::class, $writer);
    }

    public function testWriteSingleFile()
    {
        $writer = new CsvWriter(
            ';',
            true,
            new Map('string', FormatterInterface::class)
        );

        $file = $writer->write(
            (new Spreadsheet('spreadsheet'))
                ->add(
                    (new Sheet('sheet'))
                        ->add(
                            new Cell(
                                new Position('A', 1),
                                'a1'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('A', 2),
                                'a2'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('B', 1),
                                'b1'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('B', 2),
                                'b2'
                            )
                        )
                )
        );

        $this->assertInstanceOf(Csv::class, $file);
        $this->assertSame('sheet.csv', (string) $file->name());
        $this->assertSame(<<<CSV
A;B
a1;b1
a2;b2

CSV
            ,
            (string) $file->content()
        );
    }

    public function testWriteSingleFileWithoutHeader()
    {
        $writer = new CsvWriter(
            ';',
            false,
            new Map('string', FormatterInterface::class)
        );

        $file = $writer->write(
            (new Spreadsheet('spreadsheet'))
                ->add(
                    (new Sheet('sheet'))
                        ->add(
                            new Cell(
                                new Position('A', 1),
                                'a1'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('A', 2),
                                'a2'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('B', 1),
                                'b1'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('B', 2),
                                'b2'
                            )
                        )
                )
        );

        $this->assertInstanceOf(Csv::class, $file);
        $this->assertSame('sheet.csv', (string) $file->name());
        $this->assertSame(<<<CSV
a1;b1
a2;b2

CSV
            ,
            (string) $file->content()
        );
    }

    public function testWriteDirectory()
    {
        $writer = new CsvWriter(
            ';',
            true,
            new Map('string', FormatterInterface::class)
        );

        $directory = $writer->write(
            (new Spreadsheet('spreadsheet'))
                ->add(
                    (new Sheet('sheet'))
                        ->add(
                            new Cell(
                                new Position('A', 1),
                                'a1'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('A', 2),
                                'a2'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('B', 1),
                                'b1'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('B', 2),
                                'b2'
                            )
                        )
                )
                ->add(
                    (new Sheet('sheet2'))
                        ->add(
                            new Cell(
                                new Position(1, 1),
                                11
                            )
                        )
                )
        );

        $this->assertInstanceOf(DirectoryInterface::class, $directory);
        $this->assertSame('spreadsheet', (string) $directory->name());
        $this->assertCount(2, $directory);
        $file = $directory->get('sheet.csv');
        $this->assertInstanceOf(Csv::class, $file);
        $this->assertSame('sheet.csv', (string) $file->name());
        $this->assertSame(<<<CSV
A;B
a1;b1
a2;b2

CSV
            ,
            (string) $file->content()
        );
        $file = $directory->get('sheet2.csv');
        $this->assertInstanceOf(Csv::class, $file);
        $this->assertSame('sheet2.csv', (string) $file->name());
        $this->assertSame(<<<CSV
1
11

CSV
            ,
            (string) $file->content()
        );
    }

    public function testWriteWithMissingCells()
    {
        $writer = new CsvWriter(
            ';',
            false,
            new Map('string', FormatterInterface::class)
        );

        $file = $writer->write(
            (new Spreadsheet('spreadsheet'))
                ->add(
                    (new Sheet('sheet'))
                        ->add(
                            new Cell(
                                new Position('A', 2),
                                'a2'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('B', 1),
                                'b1'
                            )
                        )
                        ->add(
                            new Cell(
                                new Position('C', 2),
                                'c2'
                            )
                        )
                )
        );

        $this->assertInstanceOf(Csv::class, $file);
        $this->assertSame('sheet.csv', (string) $file->name());
        $this->assertSame(<<<CSV
;b1;
a2;;c2

CSV
            ,
            (string) $file->content()
        );
    }

    /**
     * @expectedException Spreadsheet\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyDelimiter()
    {
        new CsvWriter(
            '',
            false,
            new Map('string', FormatterInterface::class)
        );
    }

    /**
     * @expectedException Spreadsheet\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidFormatterMap()
    {
        new CsvWriter(
            ';',
            false,
            new Map('string', 'object')
        );
    }

    public function testWriteWithCorrectFormat()
    {
        $writer = new CsvWriter(
            ';',
            false,
            (new Map('string', FormatterInterface::class))
                ->put(
                    DateCell::class,
                    new DateFormatter('d/m/Y')
                )
        );

        $file = $writer->write(
            (new Spreadsheet('foo'))
                ->add(
                    (new Sheet('foo'))->add(
                        new DateCell(
                            new Position(1, 1),
                            new \DateTimeImmutable('2016-07-14')
                        )
                    )
                )
        );

        $this->assertSame(
            '14/07/2016' . "\n",
            (string) $file->content()
        );
    }
}
