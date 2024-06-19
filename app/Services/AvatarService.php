<?php

namespace App\Services;

use App\Factories\FileStorage\ProfileImageFactory;
use App\Repositories\AvatarRepository;
use Hashids\Hashids;

class AvatarService
{
    private ProfileImageFactory $profile_image_factory;

    private AvatarRepository $avatar_repository;

    public function __construct(ProfileImageFactory $profile_image_factory, AvatarRepository $avatar_repository)
    {
        $this->profile_image_factory = $profile_image_factory;
        $this->avatar_repository = $avatar_repository;
    }

    public function uploadAvatar(array $data): string
    {
        $file_storage_service = $this->profile_image_factory->getInstance();
        $filename = $this->createFileName($data['user']->id);
        $file = $data['file'];
        $full_name = $filename . '.' . $file->extension();
        $this->avatar_repository->saveAvatarPath($data['user']->id, $full_name);
        $path = url($file_storage_service->upload($full_name, $file));

        return $path;
    }

    public function deleteAvatar(array $data): void
    {
        $avatar = $this->avatar_repository->getAvatarName($data['user']->id);
        $file_storage_service = $this->profile_image_factory->getInstance();
        $file_storage_service->delete($avatar);
        $this->avatar_repository->deleteAvatarPath($data['user']->id);
    }

    private function createFileName(int $user_id): string
    {
        $hashids = new Hashids();
        $random_string = bin2hex(random_bytes(8));

        return $hashids->encode($user_id) . '_' . $random_string;
    }
}
