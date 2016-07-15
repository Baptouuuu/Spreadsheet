<?php
declare(strict_types = 1);

namespace Tests\Spreadsheet\File;

use Spreadsheet\File\Csv;
use Innmind\Filesystem\{
    FileInterface,
    Stream\StringStream
};

class CsvTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $csv = new Csv('foo', new StringStream('bar'));

        $this->assertInstanceOf(FileInterface::class, $csv);
        $this->assertSame('foo.csv', (string) $csv->name());
        $this->assertSame('bar', (string) $csv->content());
        $this->assertSame('text/csv', (string) $csv->mediaType());
    }
}
