<?php
declare(strict_types = 1);

namespace Spreadsheet\File;

use Innmind\Filesystem\{
    File,
    StreamInterface,
    MediaType\MediaType
};

final class Csv extends File
{
    public function __construct(string $name, StreamInterface $content)
    {
        parent::__construct(
            $name.'.csv',
            $content,
            MediaType::fromString('text/csv')
        );
    }
}
