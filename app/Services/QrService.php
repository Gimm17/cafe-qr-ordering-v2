<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QrService
{
    public function png(string $data, int $size = 320): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->size($size)
            ->margin(10)
            ->build();

        return $result->getString(); // PNG binary
    }
}
