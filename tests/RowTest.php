<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet;

use Spreadsheet\{
    Row,
    RowInterface,
    CellInterface
};
use Innmind\Immutable\Map;

class RowTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $column = new Row(
            42,
            $cells = new Map('scalar', CellInterface::class)
        );

        $this->assertInstanceOf(RowInterface::class, $column);
        $this->assertSame(42, $column->identifier());
        $this->assertSame($cells, $column->cells());
    }

    /**
     * @expectedException Spreadsheet\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidIdentifier()
    {
        new Row([], new Map('scalar', CellInterface::class));
    }

    /**
     * @expectedException Spreadsheet\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidCellMap()
    {
        new Row('A', new Map('string', CellInterface::class));
    }
}
