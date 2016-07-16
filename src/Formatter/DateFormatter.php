<?php
declare(strict_types = 1);

namespace Spreadsheet\Formatter;

use Spreadsheet\{
    CellInterface,
    Exception\InvalidArgumentException
};

final class DateFormatter implements FormatterInterface
{
    private $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function format(CellInterface $cell): string
    {
        if (!$cell->value() instanceof \DateTimeInterface) {
            throw new InvalidArgumentException;
        }

        return $cell->value()->format($this->format);
    }
}
