@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('List of All Users') }}</div>

                <div class="card-body">
                    <a href="/users/index">Show All Users</a>
                    <table class="table">
                        <thead class="thead-dark">
                            <th>id</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Photo</th>
                            <th>Type</th>
                            <th>Action</th>
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
                                    <th>
                                        <a href="/users/show/{{$user->id}}" class="btn btn-info">View</a>
                                        <a href="/users/edit/{{$user->id}}" class="btn btn-success">Edit</a>
                                        @if (is_null($user->deleted_at))
                                            <a href="/users/destroy/{{$user->id}}" class="btn btn-danger">Delete</a>
                                        @else
                                            <a href="/users/restore/{{$user->id}}" class="btn btn-warning">Restore</a>
                                            <a href="/users/delete/{{$user->id}}" class="btn btn-danger">Hard Delete</a>
                                        @endif
                                    </th>
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