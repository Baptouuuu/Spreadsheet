<?php
declare(strict_types = 1);

namespace Spreadsheet\Formatter;

use Spreadsheet\CellInterface;

interface FormatterInterface
{
    public function format(CellInterface $cell): string;
}
