<?php

namespace App\Repositories;

use App\Models\User;

class AvatarRepository
{
    public function saveAvatarPath(int $userId, string $filename): void
    {
        $user = User::find($userId);
        $user->update(['avatar_name' => $filename]);
    }

    public function getAvatarName(int $userId)
    {
        $user = User::find($userId);
        $avatar = $user->avatar_name;
        $pathInfo = pathinfo($avatar);

        return $pathInfo['basename'];
    }

    public function deleteAvatarPath(int $userId): void
    {
        $user = User::find($userId);
        $user->update(['avatar_name' => null]);
    }
}
