<?php
declare(strict_types = 1);

namespace Spreadsheet;

use Spreadsheet\Exception\InvalidArgumentException;

final class Position
{
    private $column;
    private $row;

    public function __construct($column, $row)
    {
        if (!is_scalar($column) || !is_scalar($row)) {
            throw new InvalidArgumentException;
        }

        $this->column = $column;
        $this->row = $row;
    }

    public function column()
    {
        return $this->column;
    }

    public function row()
    {
        return $this->row;
    }
}
