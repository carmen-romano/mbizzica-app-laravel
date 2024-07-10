<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paste;
use App\Http\Controllers\PasteController;

class HomeController extends Controller
{
    protected $pasteController;

    public function __construct(PasteController $pasteController)
    {
        $this->pasteController = $pasteController;
    }

    public function index(Request $request)
    {
        $query = Paste::orderBy('created_at', 'DESC');

        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }
        $pastesFromDB = $query->get();

        $pastesFromFile = $this->pasteController->pasteHome();
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $pastesFromFile = array_filter($pastesFromFile, function ($paste) use ($searchTerm) {
                return stripos($paste['title'], $searchTerm) !== false || stripos($paste['content'], $searchTerm) !== false;
            });
        }

        $pastesFromFileCollection = collect($pastesFromFile)->map(function ($item) {
            return new Paste($item);
        });

        $combinedData = $pastesFromDB->merge($pastesFromFileCollection);

        return view('home', ['allPastes' => $combinedData]);
    }
}
