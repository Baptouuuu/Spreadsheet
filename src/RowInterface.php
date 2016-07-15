<?php
declare(strict_types = 1);

namespace Spreadsheet;

use Innmind\Immutable\MapInterface;

interface RowInterface
{
    /**
     * @return scalar
     */
    public function identifier();

    /**
     * @return MapInterface<scalar, CellInterface>
     */
    public function cells(): MapInterface;
}
