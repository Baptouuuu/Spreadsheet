<?php
declare(strict_types = 1);

namespace Spreadsheet;

use Innmind\Immutable\MapInterface;

interface SpreadsheetInterface
{
    public function name(): string;

    /**
     * @return MapInterface<string, SheetInterface>
     */
    public function sheets(): MapInterface;

    public function add(SheetInterface $sheet): self;
    public function replace(SheetInterface $sheet): self;
    public function has(string $sheet): bool;
    public function get(string $sheet): SheetInterface;
}
