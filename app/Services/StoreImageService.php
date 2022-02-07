<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreImageService
{
    public function store(UploadedFile $image)
    {
        $path = 'users/' . Carbon::today()->format('Y/m/d');
        $disk = 'public';

        if (!Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->makeDirectory($path);
        }

        $path = Storage::disk($disk)->putFile($path, $image);

        return $path;
    }

    public function url($path)
    {
        $disk = 'public';
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        } else {
            return null;
        }
    }
}
