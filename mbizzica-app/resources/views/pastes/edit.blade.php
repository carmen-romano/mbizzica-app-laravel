@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">Modifica Paste</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('pastes.update', $paste->id) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="title">Titolo</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $paste->title }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="content">Contenuto</label>
                            <textarea class="form-control" id="content" name="content" rows="5" required>{{ $paste->content }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="visibility">Visibilità</label>
                            <select class="form-control" id="visibility" name="visibility" required>
                                <option value="public" {{ $paste->visibility == 'public' ? 'selected' : '' }}>Pubblica</option>
                                <option value="private" {{ $paste->visibility == 'private' ? 'selected' : '' }}>Privata</option>
                                <option value="unlisted" {{ $paste->visibility == 'unlisted' ? 'selected' : '' }}>Non in elenco</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="expires_at">Data di Scadenza</label>
                            <input type="date" class="form-control" id="expires_at" name="expires_at" value="{{ $paste->expires_at }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="{{ $paste->password }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="file">File</label>
                            <input type="file" class="form-control" id="file" name="file">
                        </div>

                        <button type="submit" class="btn btn-primary">Salva</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
