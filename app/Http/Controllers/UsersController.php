<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->User = new User();
    }

    /**
     * shows list of users
     */
    public function index() {
        $users = User::select(
            'id',
            'prefixname',
            'firstname',
            'middlename',
            'lastname',
            'suffixname',
            'username',
            'email',
            'photo',
            'type'
        )->get();

        return view('users.index', ['users' => $users]);
    }

    /**
     * for adding new user
     */
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($filename = Storage::disk('public')->put('users', $request->file('photo'))) {
                User::create([
                    'prefixname' => $request['prefixname'],
                    'firstname' => $request['firstname'],
                    'middlename' => $request['middlename'],
                    'lastname' => $request['lastname'],
                    'suffixname' => $request['suffixname'],
                    'email' => $request['email'],
                    'photo' => $filename,
                    'username' => $request['username'],
                    'password' => Hash::make($request['password']),
                    'email_verified_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        return view('users.create');
    }

    public function edit()
    {
        return view('users.edit');
    }
}
