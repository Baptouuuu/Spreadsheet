<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet\Formatter;

use Spreadsheet\{
    Formatter\DateFormatter,
    Formatter\FormatterInterface,
    Cell,
    Cell\DateCell,
    Position
};

class DateFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            FormatterInterface::class,
            new DateFormatter('Y')
        );
    }

    public function testFormat()
    {
        $formatter = new DateFormatter('Y-m-d');

        $this->assertSame(
            '2016-07-14',
            $formatter->format(new DateCell(
                new Position(1, 1),
                new \DateTimeImmutable('2016-07-14')
            ))
        );
    }

    /**
     * @expectedException Spreadsheet\Exception\InvalidArgumentException
     */
    public function testThrowWhenUnsupportedValue()
    {
        (new DateFormatter('Y'))->format(new Cell(
            new Position(1, 1),
            '2016-07-14'
        ));
    }
}
