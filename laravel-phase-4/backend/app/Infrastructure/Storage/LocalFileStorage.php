<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage;

use Illuminate\Support\Str;

final class LocalFileStorage
{
    public function save(string $contents, string $extension): string
    {
        $directory = storage_path('app/public/media');

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $key = Str::uuid()->toString() . '.' . ltrim($extension, '.');
        file_put_contents($directory . DIRECTORY_SEPARATOR . $key, $contents);

        return $key;
    }
}
