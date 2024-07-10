<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Paste;

class VoteController extends Controller
{
    public function store(Request $request, Paste $paste)
    {
        $request->validate([
            'is_upvote' => 'required|boolean',
        ]);


        $existingVote = Vote::where('user_id', auth()->id())
            ->where('paste_id', $paste->id)
            ->first();

        if ($existingVote) {
            $existingVote->is_upvote = $request->input('is_upvote');
            $existingVote->save();
        } else {
            $vote = new Vote([
                'user_id' => auth()->id(),
                'paste_id' => $paste->id,
                'is_upvote' => $request->input('is_upvote'),
            ]);

            $vote->save();
        }

        return redirect()->route('pastes.show', $paste->id)->with('success', 'Voto registrato con successo!');
    }
}
