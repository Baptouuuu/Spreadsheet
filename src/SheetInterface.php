<?php
declare(strict_types = 1);

namespace Spreadsheet;

use Innmind\Immutable\MapInterface;

interface SheetInterface
{
    public function name(): string;

    /**
     * @return MapInterface<scalar, ColumnInterface>
     */
    public function columns(): MapInterface;

    /**
     * @return MapInterface<scalar, RowInterface>
     */
    public function rows(): MapInterface;

    public function add(CellInterface $cell): self;
    public function replace(CellInterface $cell): self;
    public function get(Position $position): CellInterface;
    public function has(Position $position): bool;
}
