<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Paste;

class CommentController extends Controller
{
    public function store(Request $request, Paste $paste)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = new Comment([
            'user_id' => auth()->id(),
            'paste_id' => $paste->id,
            'content' => $request->input('content'),

        ]);

        $comment->save();

        return redirect()->route('pastes.show', $paste->id)->with('success', 'Commento aggiunto con successo!');
    }
}
