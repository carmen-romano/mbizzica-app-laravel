@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center fs-4">{{ __('Crea un nuovo Paste') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('pastes.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Title') }}</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}">
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="content" class="col-md-4 col-form-label text-md-end">{{ __('Content') }}</label>
                            <div class="col-md-6">
                                <textarea id="content" class="form-control @error('content') is-invalid @enderror" name="content" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="file" class="col-md-4 col-form-label text-md-end">{{ __('File') }}</label>
                            <div class="col-md-6">
                                <input id="file" type="file" class="form-control @error('file') is-invalid @enderror" name="file">
                                @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="expires_at" class="col-md-4 col-form-label text-md-end">{{ __('Expires At') }}</label>
                            <div class="col-md-6">
                                <input id="expires_at" type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" name="expires_at" value="{{ old('expires_at') }}">
                                @error('expires_at')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @auth
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    @if ($errors->has('password'))
                                        <input id="password" type="password" class="form-control is-invalid" name="password">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @else
                                        <input id="password" type="password" class="form-control" name="password" value="">
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="visibility" class="col-md-4 col-form-label text-md-end">{{ __('Visibility') }}</label>
                                <div class="col-md-6">
                                    <select id="visibility" class="form-control @error('visibility') is-invalid @enderror" name="visibility" required>
                                        <option value="public">{{ __('Public') }}</option>
                                        <option value="private">{{ __('Private') }}</option>
                                        <option value="unlisted">{{ __('Unlisted') }}</option>
                                    </select>
                                    @error('visibility')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="visibility" value="public">
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password" value="" disabled>
                                    <small class="text-muted">Devi essere loggato per inserire una password.</small>
                                </div>
                            </div>
                        @endauth

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create Paste') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
