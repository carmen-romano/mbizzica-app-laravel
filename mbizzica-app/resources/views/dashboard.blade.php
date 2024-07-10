@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="header text-center mb-4">
        <h1>Benvenuto nella tua Dashboard, {{ $user ? $user->name : 'Guest' }}!</h1>
        <p class="lead">Gestisci i tuoi Paste e visualizza le analisi.</p>
    </div>

    @if ($user)
        <!-- User Info Section -->
        <div class="row mb-5">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">I tuoi dati:</h5>
                        <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
                        <p class="card-text"><strong>Registrato il:</strong> {{ $user->created_at->format('d-m-Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Overview Card -->
            <div class="col-md-4 mb-4">
                <div class="card border-primary h-100">
                    <div class="card-body">
                        <h5 class="card-title">Overview</h5>
                        <p class="card-text">Visualizza un riepilogo delle tue attività.</p>
                        <a href="{{ route('pastes.user') }}" class="btn btn-primary">Visualizza</a>
                    </div>
                </div>
            </div>

            <!-- Create New Paste Card -->
            <div class="col-md-4 mb-4">
                <div class="card border-success h-100">
                    <div class="card-body">
                        <h5 class="card-title">Crea un nuovo Paste</h5>
                        <p class="card-text">Crea rapidamente un nuovo Paste e condividilo.</p>
                        <a href="{{ route('pastes.create') }}" class="btn btn-success">Crea Paste</a>
                    </div>
                </div>
            </div>

            <!-- Paste Community Card -->
            <div class="col-md-4 mb-4">
                <div class="card border-info h-100">
                    <div class="card-body">
                        <h5 class="card-title">Community</h5>
                        <p class="card-text">Visualizza i Paste pubblicati dalla community.</p>
                        <a href="{{ route('home') }}" class="btn btn-info">Visualizza</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h2>Attività Recenti</h2>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Titolo</th>
                                <th>Creato il</th>
                                <th>Scadenza</th>
                                <th>Protezione con Password</th>
                                <th>Visibilità</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPastes as $paste)
                                <tr>
                                    <td>{{ $paste->title }}</td>
                                    <td>{{ $paste->created_at->format('d-m-Y H:i') }}</td>
                                    <td>{{ $paste->expires_at ? date('d/m/y H:i', strtotime($paste->expires_at)) : '-' }}</td>
                                    <td>{{ $paste->password ? 'Attiva' : 'Non attiva' }}</td>
                                    <td>{{ ucfirst($paste->visibility) }}</td>
                                    <td class="d-flex align-items-center">
                                        @if ($paste->password && !isset($passwordValidated))
                                            <form action="{{ route('pastes.password', $paste->id) }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="password" name="password" id="password" class="form-control" required placeholder="Inserisci la password">
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-outline-primary col-12 mt-2">Visualizza</button>
                                                @error('password')
                                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </form>
                                        @else
                                            <a href="{{ route('pastes.show', $paste->id) }}" class="btn btn-sm btn-outline-primary mt-2">Visualizza</a>
                                        @endif

                                        <a href="{{ route('pastes.edit', $paste->id) }}" class="btn btn-sm btn-warning mx-2 mt-2">Modifica</a>
                                        <form action="{{ route('pastes.delete', $paste->id) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo paste?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm mt-2">Elimina Paste</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    @else
        <div class="row">
            <div class="col-12 text-center">
                <p>Vai al <a href="{{ route('login') }}">login</a> per visualizzare la tua dashboard.</p>
            </div>
        </div>
    @endif
</div>
@endsection