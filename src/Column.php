<?php
declare(strict_types = 1);

namespace Spreadsheet;

use Spreadsheet\Exception\InvalidArgumentException;
use Innmind\Immutable\MapInterface;

final class Column implements ColumnInterface
{
    private $identifier;
    private $cells;

    public function __construct($identifier, MapInterface $cells)
    {
        if (
            !is_scalar($identifier) ||
            (string) $cells->keyType() !== 'scalar' ||
            (string) $cells->valueType() !== CellInterface::class
        ) {
            throw new InvalidArgumentException;
        }

        $this->identifier = $identifier;
        $this->cells = $cells;
    }

    /**
     * {@inheritdoc}
     */
    public function identifier()
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function cells(): MapInterface
    {
        return $this->cells;
    }
}
