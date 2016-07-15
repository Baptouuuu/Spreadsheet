<?php
declare(strict_types = 1);

namespace Spreadsheet;

interface CellInterface
{
    public function position(): Position;

    /**
     * @return mixed
     */
    public function value();

    public function __toString(): string;
}
