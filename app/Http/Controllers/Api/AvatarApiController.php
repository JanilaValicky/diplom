<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AvatarUploadRequest;
use App\Services\AvatarService;
use Illuminate\Http\Request;

class AvatarApiController extends AbstractApiController
{
    private AvatarService $avatarService;

    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
    }

    public function upload(AvatarUploadRequest $request)
    {
        $path = $this->avatarService->uploadAvatar([
            'user' => $request->user(),
            'file' => $request->file('profile_picture'),
        ]);

        return $this->createResponse([
            'path' => $path,
        ]);
    }

    public function delete(Request $request)
    {
        $this->avatarService->deleteAvatar([
            'user' => $request->user(),
        ]);

        return $this->createResponse([
            'message' => 'Avatar deleted',
        ]);
    }
}
