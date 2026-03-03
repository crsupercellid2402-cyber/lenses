<?php

namespace App\Http\Controllers\Dashboard\Api;

use App\Models\Image;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ImageController
{
    public function deletePhoto(
        string $folderName,
        string $fileName
    ): \Illuminate\Foundation\Application|Response|Application|ResponseFactory {
        $url = $folderName.'/'.$fileName;
        Image::query()->where('url', $url)->delete();
        Storage::disk('public')->delete($url);

        return response([], 204);
    }
}
