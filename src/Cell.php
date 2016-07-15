<?php
declare(strict_types = 1);

namespace Spreadsheet;

final class Cell implements CellInterface
{
    private $position;
    private $value;
    private $string;

    public function __construct(Position $position, $value)
    {
        $this->position = $position;
        $this->value = $value;
        $this->string = (string) $value;
    }

    public function position(): Position
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function value()
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
