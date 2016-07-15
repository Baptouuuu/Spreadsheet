<?php
declare(strict_types = 1);

namespace Spreadsheet\Writer;

use Spreadsheet\SpreadsheetInterface;
use Innmind\Filesystem\FileInterface;

interface WriterInterface
{
    public function write(SpreadsheetInterface $spreadsheet): FileInterface;
}
