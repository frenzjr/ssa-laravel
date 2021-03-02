@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('List of Users') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead class="thead-dark">
                            <th>id</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Photo</th>
                            <th>Type</th>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th>{{$user['id']}}</th>
                                    <th>{{$user['username']}}</th>
                                    <th>
                                        @php
                                            {{ echo implode(' ', [$user['prefixname'], $user['firstname'], $user['middlename'], $user['lastname'], $user['suffixname']]); }}
                                        @endphp
                                    </th>
                                    <th>{{$user['email']}}</th>
                                    <th>
                                        @if (!is_null($user['photo']))
                                            <img src="/storage/{{ $user['photo']}}" alt="" class="img-thumbnail">
                                        @endif
                                    </th>
                                    <th>{{$user['type']}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection