<?php
declare(strict_types = 1);

namespace Spreadsheet;

use Spreadsheet\Exception\{
    CellAlreadyExistsException,
    CellNotFoundException
};
use Innmind\Immutable\{
    Map,
    MapInterface
};

final class Sheet implements SheetInterface
{
    private $name;
    private $columns;
    private $rows;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->columns = new Map('scalar', ColumnInterface::class);
        $this->rows = new Map('scalar', RowInterface::class);
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function columns(): MapInterface
    {
        return $this->columns;
    }

    /**
     * {@inheritdoc}
     */
    public function rows(): MapInterface
    {
        return $this->rows;
    }

    public function add(CellInterface $cell): SheetInterface
    {
        if ($this->has($cell->position())) {
            throw new CellAlreadyExistsException;
        }

        return $this->position($cell);
    }

    public function replace(CellInterface $cell): SheetInterface
    {
        if (!$this->has($cell->position())) {
            throw new CellNotFoundException;
        }

        return $this->position($cell);
    }

    public function get(Position $position): CellInterface
    {
        if (!$this->has($position)) {
            throw new CellNotFoundException;
        }

        return $this
            ->columns
            ->get($position->column())
            ->cells()
            ->get($position->row());
    }

    public function has(Position $position): bool
    {
        if (
            $this->columns->contains($position->column()) &&
            $this->columns->get($position->column())->cells()->contains($position->row())
        ) {
            return true;
        }

        return false;
    }

    private function position(CellInterface $cell): self
    {
        $column = $cell->position()->column();
        $row = $cell->position()->row();
        $columns = $this->columns;
        $rows = $this->rows;

        if (!$columns->contains($column)) {
            $columns = $columns->put(
                $column,
                new Column($column, new Map('scalar', CellInterface::class))
            );
        }

        if (!$rows->contains($row)) {
            $rows = $rows->put(
                $row,
                new Row($row, new Map('scalar', CellInterface::class))
            );
        }

        $columns = $columns->put(
            $column,
            new Column(
                $column,
                $columns->get($column)->cells()->put(
                    $row,
                    $cell
                )
            )
        );
        $rows = $rows->put(
            $row,
            new Row(
                $row,
                $rows->get($row)->cells()->put(
                    $column,
                    $cell
                )
            )
        );

        $sheet = new self($this->name);
        $sheet->rows = $rows;
        $sheet->columns = $columns;

        return $sheet;
    }
}
