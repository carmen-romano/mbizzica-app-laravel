@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center fs-4">{{ $pasteData['title'] }}</div>
                <div class="card-body">
                    @if($pasteData['password'])
                        <p class="text-danger">Questo paste è protetto da password e non può essere visualizzato.</p>
                    @else
                        <p>{{ $pasteData['content'] }}</p>
                        <p><strong>Visibility:</strong> {{ ucfirst($pasteData['visibility']) }}</p>
                        @if ($pasteData['expires_at'])
                            <p><strong>Expires at:</strong> {{ date('d/m/y H:i', strtotime($pasteData['expires_at'])) }}</p>
                        @endif

                        <!-- Sezione per i tag -->
                        <div class="mt-4">
                            <h5>Tags:</h5>
                            <ul class="list-group">
                                @forelse($tags ?? [] as $tag)
                                    <li class="list-group-item">{{ $tag->tag }}</li>
                                @empty
                                    <li class="list-group-item">No tags available.</li>
                                @endforelse
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
