<?php
declare(strict_types = 1);

namespace Spreadsheet\Cell;

use Spreadsheet\{
    CellInterface,
    Position
};

final class DateCell implements CellInterface
{
    private $position;
    private $date;
    private $string;

    public function __construct(
        Position $position,
        \DateTimeImmutable $date
    ) {
        $this->position = $position;
        $this->date = $date;
        $this->string = $date->format(\DateTime::ISO8601);
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
        return $this->date;
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
