<?php

namespace App\Http\Controllers;

use App\Mail\notifications;
use Illuminate\Http\Request;
use App\Models\Paste;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\Vote;
use Illuminate\Support\Facades\Mail;



class PasteController extends Controller
{
    public function create()
    {
        return view('pastes.create');
    }


    public function show($id)
    {
        $paste = Paste::with('comments')->findOrFail($id);
        $tags = $paste->tags()->get();
        $passwordValidated = session()->get('passwordValidated_' . $paste->id);


        $upvotes = $paste->votes()->where('is_upvote', true)->count();
        $downvotes = $paste->votes()->where('is_upvote', false)->count();

        return view('pastes.show', compact('paste', 'tags', 'upvotes', 'downvotes'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([

            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'visibility' => 'required|in:public,private,unlisted',
            'expires_at' => 'nullable|date',
            'password' => 'nullable|string|min:6',
            'file' => 'nullable|file|mimes:txt,pdf,jpg|max:2048',
            'g-recaptcha-response' => 'required|captcha',

        ]);

        // Gestisci il file upload
        $file_path = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if (!$file->isValid()) {
                return redirect()->back()->with('error', 'Il file non è valido.');
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            Storage::disk('local')->putFileAs('pastes', $file, $filename);
            $file_path = 'pastes/' . $filename;
        }

        $pasteData = [
            'title' => $data['title'],
            'content' => $data['content'],
            'visibility' => $data['visibility'],
            'expires_at' => $data['expires_at'],
            'password' => isset($data['password']) ? Hash::make($data['password']) : null,
            'file' => $file_path,
        ];


        // Se l'utente è autenticato, salva il paste nel database e nel file json
        if (Auth::check()) {
            $paste = auth()->user()->pastes()->create($pasteData);
            $this->savePasteToFile($paste);
            return redirect()->route('pastes.show', $paste->id);
        } else {
            // Altrimenti, salva temporaneamente il paste in un file JSON
            $pasteData['id'] = Str::uuid()->toString();
            $this->savePasteToFile($pasteData);
            return view('message.success')->with('message', 'Paste creato con successo!');
        }
    }

    private function savePasteToFile($pasteData)
    {
        try {
            $fileContent = Storage::disk('local')->exists('pastes/all.json') ?
                Storage::disk('local')->get('pastes/all.json') :
                '[]';

            $existingPastes = json_decode($fileContent, true);
            $existingPastes[] = $pasteData;

            Storage::disk('local')->put('pastes/all.json', json_encode($existingPastes));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function take($id, $slug)
    {

        // Cerca nel file all.json
        $allPastes = $this->getAllPastes();

        foreach ($allPastes as $paste) {

            return view('pastes.take', [
                'pasteData' => $paste,
                'id' => $id,
                'slug' => $slug,
            ]);
        }
    }




    public function userPastes()
    {
        $user = Auth::user();
        $pastes = $user->pastes()->latest()->get();
        return view('pastes.user', compact('pastes'));
    }
    public function pasteHome()
    {
        try {
            $fileContent = Storage::disk('local')->get('pastes/all.json');
            $pastesFromFile = json_decode($fileContent, true);
            $pastesFromFile = array_map(function ($paste) {
                if (is_string($paste)) {
                    return json_decode($paste, true);
                }
                return $paste;
            }, $pastesFromFile);
            $publicPastes = array_filter($pastesFromFile, function ($paste) {
                return isset($paste['visibility']) && $paste['visibility'] === 'public';
            });
            // Preparo l'array finale dei paste da visualizzare
            $formattedPastes = [];
            foreach ($publicPastes as $pasteData) {
                $id = isset($pasteData['id']) ? $pasteData['id'] : Str::uuid()->toString();
                $formattedPastes[] = [
                    'id' => $id,
                    'title' => $pasteData['title'] ?? 'No Title',
                    'content' => $pasteData['content'],
                    'expires_at' => $pasteData['expires_at'] ?? null,
                    'password' => $pasteData['password'] ?? null,
                    'file' => $pasteData['file'] ?? null,
                ];
            }
            return $formattedPastes;
        } catch (\Exception $e) {
            return [];
        }
    }


    public function edit(Paste $paste)
    {
        return view('pastes.edit', compact('paste'));
    }


    public function delete(Paste $paste)
    {
        $paste->delete();
        return redirect()->route('dashboard');
    }

    public function validatePassword(Request $request, $id)
    {
        $paste = Paste::findOrFail($id);

        if (Hash::check($request->input('password'), $paste->password)) {
            session(['passwordValidated_' . $paste->id => true]);
            return redirect()->route('pastes.show', $paste->id);
        } else {
            return back()->withErrors(['password' => 'Password errata.'])->withInput();
        }
    }


    public function update(Request $request, Paste $paste)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'visibility' => 'required|in:public,private,unlisted',
            'expires_at' => 'nullable|date',
            'password' => 'nullable|string|min:6',
            'file' => 'nullable|file|mimes:txt,pdf,jpg|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $filename = time() . '_' . $file->getClientOriginalName();
                Storage::disk('local')->putFileAs('pastes', $file, $filename);
                $paste->file = 'pastes/' . $filename;
            }
        }
        $paste->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'visibility' => $data['visibility'],
            'expires_at' => $data['expires_at'],
            'password' => $data['password'] ? Hash::make($data['password']) : $paste->password,
            'file' => $paste->file,
        ]);

        return redirect()->route('pastes.show', $paste->id);
    }

    public function addComment(Request $request, $pasteId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $comment = new Comment();
        $comment->user_id = auth()->user()->id;
        $comment->paste_id = $pasteId;
        $comment->content = $request->content;
        $comment->save();

        return redirect()->route('dashboard', $pasteId);
    }

    public function likePaste($pasteId)
    {
        $paste = Paste::findOrFail($pasteId);

        $existingVote = Vote::where('user_id', auth()->user()->id)
            ->where('paste_id', $pasteId)
            ->first();

        if ($existingVote) {
            // Toggle the vote
            $existingVote->is_upvote = !$existingVote->is_upvote;
            $existingVote->save();
        } else {
            // Create a new vote
            $vote = new Vote();
            $vote->user_id = auth()->user()->id;
            $vote->paste_id = $pasteId;
            $vote->is_upvote = true; // Default to upvote
            $vote->save();
        }

        // Recalculate votes
        $upvotes = $paste->votes()->where('is_upvote', true)->count();
        $downvotes = $paste->votes()->where('is_upvote', false)->count();

        return redirect()->route('pastes.show', $pasteId)->with(compact('upvotes', 'downvotes'))->with('success', 'Vote recorded successfully!');
    }


    public function getAllPastes()
    {
        $pastesFromDB = Paste::with('comments')->get();
        $files = Storage::disk('local')->files('pastes');
        $pastesFromFile = [];

        foreach ($files as $file) {
            $fileContent = Storage::disk('local')->get($file);

            // Decodifica il contenuto JSON in un array associativo
            $pasteData = json_decode($fileContent, true);
            if ($pasteData && isset($pasteData['visibility']) && $pasteData['visibility'] === 'public') {

                // Aggiungi il paste al formato corretto
                $pastesFromFile[] = new Paste([
                    'title' => $pasteData['title'] ?? 'No Title',
                    'content' => $pasteData['content'],
                    'expires_at' => $pasteData['expires_at'] ?? null,
                    'password' => $pasteData['password'] ?? null,
                    'file' => $pasteData['file'] ?? null,
                ]);
            }
        }
        $allPastes = $pastesFromDB->merge($pastesFromFile);

        return $allPastes;
    }
}
