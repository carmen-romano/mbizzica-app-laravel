@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center bg-light text-dark">
                <div class="card-body">
                    <h4 class="card-title fw-bold">{{ $paste->title }}</h4>
                    <hr class="bg-dark">
                    <p class="card-text">{{ $paste->content }}</p>
                    <p class="card-text">Visibilità: {{ $paste->visibility }}</p>
                    <p class="card-text">Creato il: <strong>{{ $paste->created_at->format('d/m/y H:i') }}</strong></p>
                    <p>Scade il: <strong>{{ $paste->expires_at ? date('d/m/y H:i', strtotime($paste->expires_at)) : 'Non specificato' }}</strong></p>


               

                    <!-- Sezione per i tag -->
                    <div class="mt-4">
                        <h5>Tags:</h5>
                        <ul class="list-group list-group-flush">
                            @forelse($tags as $tag)
                                <li class="list-group-item">{{ $tag->tag }}</li>
                            @empty
                                <li class="list-group-item">Nessun tag disponibile.</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Sezione dei commenti -->
                    <div class="mt-4">
                        <h5>Commenti:</h5>
                        @forelse($paste->comments as $comment)
                            <div class="mt-2">
                                <p><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}</p>
                            </div>
                        @empty
                            <p>Nessun commento disponibile.</p>
                        @endforelse
                    </div>

                    <!-- Sezione dei Voti -->
                    <div class="mt-4">
                        <h5>Voti:</h5>
                        <p class="mt-2">Likes: {{ $upvotes ?? "0" }}</p>
                        <p>Dislikes: {{ $downvotes ?? "0" }}</p>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        @if(Auth::user() && Auth::user()->id == $paste->user_id)
                            <a href="{{ route('tags.create', ['paste' => $paste->id]) }}" class="btn btn-outline-primary">Aggiungi Tag</a>
                        @endif
                        <a href="{{ route('pastes.create') }}" class="btn btn-outline-success">Crea Nuovo Paste</a>
                        <a href="{{ route('home') }}" class="btn btn-info">Torna alla home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
