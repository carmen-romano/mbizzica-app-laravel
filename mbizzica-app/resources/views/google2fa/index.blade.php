@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center align-items-center">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-content">
                <div class="panel-heading font-weight-bold">Registrazione</div>
                <hr>

                @if ($errors->any())
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <strong>{{ $errors->first() }}</strong>
                    </div>
                </div>
                @endif

                <div class="panel-body">

                    <form action="{{ route('2fa') }}" class="form-horizontal" method="post">
                        @csrf
                        <div class="form-group">
                            <p>Inserisci <strong>OTP</strong> generato dalla tua App di Autenticazione. <br>
                            Assicurati di inviare quello corrente perché si aggiorna ogni 20 secondi.</p>
                            <label for="one_time_password" class="col-md-4 custom-control-label"></label>
                            <div class="col-md-6">
                                <input type="number" name="one_time_password" id="one_time_password" required autofocus class="form-control">
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4 mt-3">
                                    <button type="submit" class="btn btn-primary">Accedi</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
