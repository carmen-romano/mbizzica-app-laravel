<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Paste;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function create(Paste $paste)
    {

        return view('tags.create', compact('paste'));
    }

    public function store(Request $request, Paste $paste)
    {
        $data = $request->validate([
            'tag' => 'required|string|max:255',
        ]);

        $tag = new Tag($data);
        $paste->tags()->save($tag);

        return redirect()->route('pastes.show', $paste->id)->with('success', 'Tag aggiunto con successo!');
    }
}
