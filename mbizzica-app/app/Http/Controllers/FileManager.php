<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class FileService extends Controller
{
    public function saveFile($file, $filePath)
    {
        try {
            if (!file_exists($file)) {
                throw new \Exception('Il file caricato non esiste.');
            }

            Storage::disk('local')->put($filePath, file_get_contents($file));
            return Storage::disk('local')->url($filePath);
        } catch (\Exception $e) {
            // Log dell'errore
            Log::error('Errore nel salvataggio del file: ' . $e->getMessage());

            // Puoi anche lanciare l'eccezione per gestirla altrove, se necessario
            throw $e;
        }
    }

    public function deleteFile($filePath)
    {
        try {
            Storage::disk('local')->delete($filePath);
        } catch (\Exception $e) {
            // Log dell'errore
            Log::error('Errore nell\'eliminazione del file: ' . $e->getMessage());
            throw $e;
        }
    }


    public function displayImage($filename)
    {
        $path = storage_path('app/public/pastes/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
