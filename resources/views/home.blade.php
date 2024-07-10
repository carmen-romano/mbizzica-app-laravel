@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center font-weight-bold my-4">Esplora i Paste pubblici condivisi dagli utenti</h1>

        <!-- Form di ricerca -->
        <div class="row justify-content-center my-4">
            <div class="col-md-6">
                <form action="{{ route('home') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cerca per titolo o contenuto">
                        <button class="btn btn-primary" type="submit">Cerca</button>
                    </div>
                </form>
            </div>
        </div>
        @if (!empty($allPastes) && count($allPastes) > 0)
            <div class="row mt-3 ">
                @foreach ($allPastes as $paste)
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <p class="card-header fs-3 fw-bold text-center">{{ $paste->title }}</p>
                            <div class="card-body">
                              
                                @if ($paste->password)
                                    <p>Questo paste è protetto da password.</p>
                                @else
                                    <p>{{ $paste->content }}</p>
                                    @if ($paste->expires_at)
                                        <p>Scade il: {{ $paste->expires_at }}</p>
                                    @endif
                                    <p>ID: {{ $paste->id }}</p>
                                @endif

                                <!-- Mostra i commenti -->
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
                                    @auth
                                        <form action="{{ route('pastes.like', $paste->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success">Like</button>
                                        </form>
                                        <form action="{{ route('pastes.like', $paste->id) }}" method="POST" class="d-inline ml-2">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger">Dislike</button>
                                        </form>
                                    @else
                                        <!-- Messaggio o azione se l'utente non è autenticato -->
                                    @endauth
                                </div>

                                <!-- Mostro il form dei commenti solo se l'utente è loggato -->
                                @auth
                                    <div class="mt-4">
                                        <form action="{{ route('comments.store', $paste->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <textarea class="form-control" name="content" rows="3" placeholder="Aggiungi un commento"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary col-12">Commenta</button>
                                        </form>
                                    </div>
                                @else
                                    <p class="mt-4"><a href="{{ route('login') }}">Accedi</a> o <a href="{{ route('register') }}">registrati</a> per poter commentare/votare.</p>
                                @endauth

                                <!-- Link per condividere il paste -->
                                @if (isset($paste['id']) && isset($paste['content']))
                                <a href="{{ route('pastes.take', ['id' => $paste['id'], 'slug' => Str::slug($paste['content'])]) }}" class="btn btn-outline-info col-12">Condividi</a>
                            @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- <!-- Paginazione -->
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    {{ $allPastes->links() }}
                </div>
            </div> --}}

        @else
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <p class="text-center">Nessun paste pubblico trovato.</p>
                </div>
            </div>
        @endif
    </div>
@endsection
