<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet\Cell;

use Spreadsheet\{
    Cell\DateCell,
    CellInterface,
    Position
};

class DateCellTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $cell = new DateCell(
            $position = new Position(1, 1),
            $date = new \DateTimeImmutable('2016-07-14')
        );

        $this->assertInstanceOf(CellInterface::class, $cell);
        $this->assertSame($position, $cell->position());
        $this->assertSame($date, $cell->value());
        $this->assertSame($date->format(\DateTime::ISO8601), (string) $cell);
    }
}
