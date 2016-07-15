<?php
declare(strict_types = 1);

namespace Spreadsheet\Reader;

use Spreadsheet\SpreadsheetInterface;
use Innmind\Filesystem\FileInterface;

interface ReaderInterface
{
    public function read(FileInterface $file): SpreadsheetInterface;
}
