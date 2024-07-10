@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center fs-4">I tuoi Paste</div>

                <div class="card-body">
                    @if($pastes->isEmpty())
                        <div class="text-center">
                            <p>Non hai creato nessun paste.</p>
                            <a href="{{ route('pastes.create') }}" class="btn btn-success mt-3">Crea Nuovo Paste</a>
                        </div>
                    @else
                        <div class="accordion" id="accordionExample">
                            @foreach($pastes as $paste)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                        <button class="accordion-button @if(!$loop->first) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" aria-expanded="@if($loop->first) true @else false @endif" aria-controls="collapse{{ $loop->index }}">
                                            Titolo: {{ $paste->title }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse @if($loop->first) show @endif" aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            @if ($paste->password && !isset($passwordValidated))
                                                <form action="{{ route('pastes.password', $paste->id) }}" method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="password">Password:</label>
                                                        <input type="password" name="password" id="password" class="form-control" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-3">Inserisci Password</button>
                                                    @error('password')
                                                        <div class="alert alert-danger mt-3">{{ $message }}</div>
                                                    @enderror
                                                </form>
                                            @else
                                                <p><strong>Visibility:</strong> {{ ucfirst($paste->visibility) }}</p>
                                                <p class="fw-bold"><strong>Created at:</strong> {{ date('d/m/y H:i', strtotime($paste->created_at)) }}</p>
                                                @if ($paste->expires_at)
                                                    <p><strong>Expires at:</strong> {{ date('d/m/y H:i', strtotime($paste->expires_at)) }}</p>
                                                @endif
                                                <div class="btn-group mt-3" role="group" aria-label="Paste Actions">
                                                    <a href="/pastes/take/{{ $paste->id.'-'.Str::slug($paste->title) }}" class="btn btn-info">Share</a>
                                                  
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
