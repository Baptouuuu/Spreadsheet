<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet;

use Spreadsheet\{
    Column,
    ColumnInterface,
    CellInterface
};
use Innmind\Immutable\Map;

class ColumnTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $column = new Column(
            'A',
            $cells = new Map('scalar', CellInterface::class)
        );

        $this->assertInstanceOf(ColumnInterface::class, $column);
        $this->assertSame('A', $column->identifier());
        $this->assertSame($cells, $column->cells());
    }

    /**
     * @expectedException Spreadsheet\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidIdentifier()
    {
        new Column([], new Map('scalar', CellInterface::class));
    }

    /**
     * @expectedException Spreadsheet\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidCellMap()
    {
        new Column('A', new Map('string', CellInterface::class));
    }
}
