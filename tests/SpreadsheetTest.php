<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet;

use Spreadsheet\{
    Spreadsheet,
    SpreadsheetInterface,
    SheetInterface,
    Sheet
};
use Innmind\Immutable\MapInterface;

class SpreadsheetTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spreadsheet = new Spreadsheet('foo');

        $this->assertInstanceOf(SpreadsheetInterface::class, $spreadsheet);
        $this->assertSame('foo', $spreadsheet->name());
        $this->assertInstanceOf(MapInterface::class, $spreadsheet->sheets());
        $this->assertSame('string', (string) $spreadsheet->sheets()->keyType());
        $this->assertSame(
            SheetInterface::class,
            (string) $spreadsheet->sheets()->valueType()
        );
    }

    public function testAdd()
    {
        $spreadsheet = new Spreadsheet('foo');

        $this->assertFalse($spreadsheet->has('foo'));
        $spreadsheet2 = $spreadsheet->add(
            $sheet = new Sheet('foo')
        );
        $this->assertNotSame($spreadsheet, $spreadsheet2);
        $this->assertFalse($spreadsheet->has('foo'));
        $this->assertTrue($spreadsheet2->has('foo'));
        $this->assertSame($spreadsheet->name(), $spreadsheet2->name());
    }

    /**
     * @expectedException Spreadsheet\Exception\SheetAlreadyExistsException
     */
    public function testThrowWhenAddingASheetThatAlreadyExists()
    {
        (new Spreadsheet('foo'))
            ->add(new Sheet('foo'))
            ->add(new Sheet('foo'));
    }

    public function testReplace()
    {
        $spreadsheet = (new Spreadsheet('foo'))->add(
            $sheet = new Sheet('foo')
        );

        $spreadsheet2 = $spreadsheet->replace(
            $sheet2 = new Sheet('foo')
        );

        $this->assertNotSame($spreadsheet, $spreadsheet2);
        $this->assertSame($sheet, $spreadsheet->get('foo'));
        $this->assertSame($sheet2, $spreadsheet2->get('foo'));
        $this->assertSame($spreadsheet->name(), $spreadsheet2->name());
    }

    /**
     * @expectedException Spreadsheet\Exception\SheetNotFoundException
     */
    public function testThrowWhenReplacingUnknownSheet()
    {
        (new Spreadsheet('foo'))->replace(
            new Sheet('foo')
        );
    }
}
