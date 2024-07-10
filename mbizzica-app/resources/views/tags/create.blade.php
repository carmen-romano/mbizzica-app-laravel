@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center fs-4">
                    Aggiungi Tag
                </div>

                <div class="card-body">
                    <form action="{{ route('pastes.tags.store', $paste->id) }}" method="post">
                        @csrf

                        <div class="mb-3 text-center">
                            <label for="tag" class="form-label mb-3">Nome del Tag</label>
                            <input type="text" class="form-control text-center my-2" id="tag" name="tag" placeholder="Inserisci il nome del tag">
                            @error('tag')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success col-12">Salva</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
