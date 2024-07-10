@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Configura l'Autenticazione a Due Fattori</div>

                <div class="card-body">
                    <p>Scansiona il codice QR sottostante con la tua app di autenticazione a due fattori (ad esempio, Google Authenticator) o inserisci manualmente la chiave segreta.</p>
                    
                    <div class="mb-3">
                        <strong>Chiave Segreta:</strong> {{ $secret }}
                    </div>
                    
                    <div class="mb-3">
                        <img src="data:image/svg+xml;base64,{{ base64_encode($QR_Image) }}" alt="Codice QR">
                    </div>

                    <div>
                        <a href="{{ route('complete.registration') }}" class="btn btn-primary">Completa la registrazione</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
