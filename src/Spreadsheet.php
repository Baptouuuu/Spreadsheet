<?php
declare(strict_types = 1);

namespace Spreadsheet;

use Spreadsheet\Exception\{
    SheetAlreadyExistsException,
    SheetNotFoundException
};
use Innmind\Immutable\{
    MapInterface,
    Map
};

final class Spreadsheet implements SpreadsheetInterface
{
    private $name;
    private $sheets;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->sheets = new Map('string', SheetInterface::class);
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function sheets(): MapInterface
    {
        return $this->sheets;
    }

    public function add(SheetInterface $sheet): SpreadsheetInterface
    {
        if ($this->has($sheet->name())) {
            throw new SheetAlreadyExistsException;
        }

        $self = new self($this->name);
        $self->sheets = $this->sheets->put(
            $sheet->name(),
            $sheet
        );

        return $self;
    }

    public function replace(SheetInterface $sheet): SpreadsheetInterface
    {
        if (!$this->has($sheet->name())) {
            throw new SheetNotFoundException;
        }

        $self = new self($this->name);
        $self->sheets = $this->sheets->put(
            $sheet->name(),
            $sheet
        );

        return $self;
    }

    public function has(string $sheet): bool
    {
        return $this->sheets->contains($sheet);
    }

    public function get(string $sheet): SheetInterface
    {
        return $this->sheets->get($sheet);
    }
}
