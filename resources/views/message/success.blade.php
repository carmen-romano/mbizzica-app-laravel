@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center fs-4">{{ __('Success Message') }}</div>

                <div class="card-body">
                    <div class="alert alert-success text-center" role="alert">
                        {{ $message }}
                    </div>

                    <p class="mt-4 text-center col-12"><a href="{{ route('home') }}" class="btn btn-primary">{{ __('Torna alla Home') }}</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
