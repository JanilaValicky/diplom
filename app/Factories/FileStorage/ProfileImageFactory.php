<?php

namespace App\Factories\FileStorage;

use App\Services\Interfaces\FileStorageServiceInterface;
use App\Services\LocalStorageService;
use App\Services\S3FileStorageService;

use function env;

class ProfileImageFactory
{
    const PROFILE_IMAGE_PATH = '/profiles';
    // http://localhost/storage/app/public/profiles/k5_d19755de45a45531.jpg
    // C:\OSPanel\domains\pollyforms.xyz\storage\app\public\profiles\k5_c290a9e266a77ff3.jpg
    // http://pollyforms.xyz/resources/assets/images/pflogo.jpg

    private FileStorageServiceInterface $service;

    public function __construct()
    {
        $this->service = match (env('FILESYSTEM_DISK')) {
            's3' => new S3FileStorageService(self::PROFILE_IMAGE_PATH),
            default => new LocalStorageService(self::PROFILE_IMAGE_PATH),
        };
    }

    public function getInstance(): FileStorageServiceInterface
    {
        return $this->service;
    }
}
