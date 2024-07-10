<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService
{
    public function saveFile($file, $filePath)
    {
        Storage::disk('local')->put($filePath, file_get_contents($file));
        return Storage::disk('local')->url($filePath);
    }

    public function deleteFile($filePath)
    {
        Storage::disk('local')->delete($filePath);
    }

    public function getFiles()
    {
        $files = Storage::disk('local')->files('pastes');

        return $files;
    }
}
