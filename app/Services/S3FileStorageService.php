<?php

namespace App\Services;

use App\Services\Interfaces\FileStorageServiceInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class S3FileStorageService implements FileStorageServiceInterface
{
    private Filesystem $storage;

    private string $base_path;

    public function __construct(string $base_path)
    {
        $config = config('filesystems.disks.s3');
        $this->base_path = $base_path;
        $this->storage = Storage::build($config);
    }

    public function upload(string $name, UploadedFile $file, bool $private = false): bool|string
    {
        if ($this->storage->exists($this->base_path . '/' . $name)) {
            return false;
        }
        $path = $this->storage->putFileAs($this->base_path, $file, $name);
        if ($private && !$path) {
            $this->storage->setVisibility($path, 'private');
        }

        return $this->getPath($name);
    }

    public function getPath(string $name): string
    {
        return $this->storage->getDriver()->temporaryUrl($this->base_path . '/' . $name, Carbon::now()->add('minutes', 5));
    }

    public function update(string $name, UploadedFile $file): bool|string
    {
        if (!$this->storage->exists($this->base_path . '/' . $name) || !$this->delete($name)) {
            return false;
        }

        return $this->upload($name, $file);
    }

    public function delete(string $name): bool
    {
        return $this->storage->delete($this->base_path . '/' . $name);
    }
}
