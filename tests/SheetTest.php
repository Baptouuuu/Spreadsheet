<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet;

use Spreadsheet\{
    Sheet,
    SheetInterface,
    Position,
    ColumnInterface,
    RowInterface,
    Cell
};
use Innmind\Immutable\MapInterface;

class SheetTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $sheet = new Sheet('foo');

        $this->assertInstanceOf(SheetInterface::class, $sheet);
        $this->assertSame('foo', $sheet->name());
        $this->assertInstanceOf(MapInterface::class, $sheet->columns());
        $this->assertInstanceOf(MapInterface::class, $sheet->rows());
        $this->assertSame('scalar', (string) $sheet->columns()->keyType());
        $this->assertSame(ColumnInterface::class, (string) $sheet->columns()->valueType());
        $this->assertSame('scalar', (string) $sheet->rows()->keyType());
        $this->assertSame(RowInterface::class, (string) $sheet->rows()->valueType());
        $this->assertCount(0, $sheet->columns());
        $this->assertCount(0, $sheet->rows());
    }

    public function testAdd()
    {
        $sheet = new Sheet('foo');

        $this->assertFalse($sheet->has(new Position('A', 42)));
        $sheet2 = $sheet->add(
            new Cell(
                new Position('A', 42),
                'foo'
            )
        );
        $this->assertNotSame($sheet, $sheet2);
        $this->assertFalse($sheet->has(new Position('A', 42)));
        $this->assertTrue($sheet2->has(new Position('A', 42)));
        $this->assertSame($sheet->name(), $sheet2->name());
    }

    /**
     * @expectedException Spreadsheet\Exception\CellAlreadyExistsException
     */
    public function testThrowWhenAddingACellThatAlreadyExists()
    {
        (new Sheet('foo'))
            ->add(
                new Cell(
                    new Position('A', 42),
                    'foo'
                )
            )
            ->add(
                new Cell(
                    new Position('A', 42),
                    'bar'
                )
            );
    }

    public function testReplace()
    {
        $sheet = (new Sheet('foo'))
            ->add(
                new Cell(
                    new Position('A', 42),
                    'foo'
                )
            );

        $sheet2 = $sheet->replace(
            new Cell(
                new Position('A', 42),
                'bar'
            )
        );

        $this->assertNotSame($sheet, $sheet2);
        $this->assertSame(
            'foo',
            $sheet->get(new Position('A', 42))->value()
        );
        $this->assertSame(
            'bar',
            $sheet2->get(new Position('A', 42))->value()
        );
        $this->assertSame($sheet->name(), $sheet2->name());
    }

    /**
     * @expectedException Spreadsheet\Exception\CellNotFoundException
     */
    public function testThrowWhenReplacingUnknownCell()
    {
        (new Sheet('foo'))->replace(
            new Cell(
                new Position('A', 42),
                'foo'
            )
        );
    }
}
