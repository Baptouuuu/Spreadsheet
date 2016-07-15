<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet;

use Spreadsheet\Position;

class PositionTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $position = new Position('foo', 'bar');

        $this->assertSame('foo', $position->column());
        $this->assertSame('bar', $position->row());

        new Position(42, 42);
        new Position(42.1, 42.1);
        new Position(42.1, 42.1);
        new Position(true, true);
    }

    /**
     * @expectedException Spreadsheet\Exception\InvalidArgumentException
     */
    public function testThrowWhenColumnIsNotAScalar()
    {
        new Position(new \stdClass, 'bar');
    }

    /**
     * @expectedException Spreadsheet\Exception\InvalidArgumentException
     */
    public function testThrowWhenRowIsNotAScalar()
    {
        new Position('foo', new \stdClass);
    }
}
