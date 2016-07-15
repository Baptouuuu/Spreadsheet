<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet;

use Spreadsheet\{
    Cell,
    CellInterface,
    Position
};

class CellTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $cell = new Cell(
            $position = new Position('A', 42),
            'some content'
        );

        $this->assertInstanceOf(CellInterface::class, $cell);
        $this->assertSame($position, $cell->position());
        $this->assertSame('some content', $cell->value());
    }
}
