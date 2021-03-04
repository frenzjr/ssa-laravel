@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update User') }}</div>

                <div class="card-body">
                    <form method="POST" enctype=multipart/form-data>
                        @csrf

                        <div class="form-group row">
                            <label for="photo" class="col-md-4 col-form-label text-md-right">{{ __('Photo') }}</label>

                            <div class="col-md-6">
                                <img src="/storage/{{$user->photo}}" alt="" style="height: 350px; width:350px" class="form-control md-right">
                                @if (is_null($user->deleted_at))
                                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                                @endif

                                @error('photo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="prefixname" class="col-md-4 col-form-label text-md-right">{{ __('Prefix') }}</label>

                            <div class="col-md-6">
                                <select name="prefixname" class="form-control @error('prefixname') is-invalid @enderror" {{!is_null($user->deleted_at) ? 'disabled' : ''}}>
                                    <option value="Mr" {{ $user->prefixname === 'Mr' ? 'SELECTED' : ''}} >Mr</option>
                                    <option value="Mrs" {{ $user->prefixname === 'Mrs' ? 'SELECTED' : ''}} >Mrs</option>
                                    <option value="Ms" {{ $user->prefixname === 'Ms' ? 'SELECTED' : ''}} >Ms</option>
                                </select>

                                @error('prefixname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') ?? $user->firstname }}" required autocomplete="firstname" {{!is_null($user->deleted_at) ? 'disabled' : ''}} autofocus>

                                @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Middle Name') }}</label>

                            <div class="col-md-6">
                                <input id="middlename" type="text" class="form-control @error('middlename') is-invalid @enderror" name="middlename" value="{{ old('middlename')  ?? $user->middlename }}" autocomplete="middlename" {{!is_null($user->deleted_at) ? 'disabled' : ''}}>

                                @error('middlename')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') ?? $user->lastname }}" required autocomplete="lastname" {{!is_null($user->deleted_at) ? 'disabled' : ''}}>

                                @error('lastname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Suffix Name') }}</label>

                            <div class="col-md-6">
                                <input id="suffixname" type="text" class="form-control @error('suffixname') is-invalid @enderror" name="suffixname" value="{{ old('suffixname')  ?? $user->suffixname }}" autocomplete="suffixname" {{!is_null($user->deleted_at) ? 'disabled' : ''}}>

                                @error('suffixname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $user->username }}" readonly>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" readonly>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if (!is_null($user->deleted_at))
                            <div class="form-group row">
                                <label for="deleted-at" class="col-md-4 col-form-label text-md-right">{{ __('Deleted at') }}</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{$user->deleted_at}}" readonly>
                                </div>
                            </div>
                        @endif

                        @if (is_null($user->deleted_at))
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update User') }}
                                    </button>
                                    <a href="/users/destroy/{{$user->id}}" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
