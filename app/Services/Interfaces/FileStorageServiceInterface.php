<?php

namespace App\Services\Interfaces;

use Illuminate\Http\UploadedFile;

interface FileStorageServiceInterface
{
    public function upload(string $name, UploadedFile $file, bool $private = false): bool|string;

    public function getPath(string $name): string;

    public function update(string $name, UploadedFile $file): bool|string;

    public function delete(string $name): bool;
}
