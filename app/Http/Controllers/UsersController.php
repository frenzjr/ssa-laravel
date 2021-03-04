<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;

class UsersController extends Controller
{
    private $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->User = DB::table('users');
        $this->userService = $userService;
    }

    /**
     * for getting user
     * @param Request $request
     *
     * @return User
     */
    private function getSingleUser(Request $request) {
        return $this->User->select(
            'id',
            'prefixname',
            'firstname',
            'middlename',
            'lastname',
            'suffixname',
            'username',
            'email',
            'photo',
            'type',
            'deleted_at'
        )->where('id', $request->route('id'))
        ->first();
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
            'type',
            'deleted_at'
        )->withTrashed()
        ->get();

        return view('users.index', ['users' => $users]);
    }

    /**
     * for adding new user
     * @param Request $request HTTP request
     *
     * @return view
     */
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $filename = null;
            $addUser = [
                'prefixname' => $request['prefixname'],
                'firstname' => $request['firstname'],
                'middlename' => $request['middlename'],
                'lastname' => $request['lastname'],
                'suffixname' => $request['suffixname'],
                'email' => $request['email'],
                'username' => $request['username'],
                'password' => Hash::make($request['password']),
                'email_verified_at' => date('Y-m-d H:i:s'),
            ];
            if (!is_null($request->file('photo'))) {
                $filename = Storage::disk('public')->put('users', $request->file('photo'));
                $addUser['photo'] = $filename;
            }

            // create user
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

        return view('users.create');
    }

    /**
     * for editing user
     * @param Request $request HTTP request
     *
     * @return view
     */
    public function edit(Request $request)
    {
        $user = $this->getSingleUser($request);
        $userId = $user->id;

        if (is_null($user)) {
            return redirect('/users/index');
        }

        if ($request->isMethod('post')) {
            $filename = null;
            $updateUser = [
                'prefixname' => $request['prefixname'],
                'firstname' => $request['firstname'],
                'middlename' => $request['middlename'],
                'lastname' => $request['lastname'],
                'suffixname' => $request['suffixname'],
                'email' => $request['email'],
                'username' => $request['username']
            ];
            if (!is_null($request->file('photo'))) {
                $filename = Storage::disk('public')->put('users', $request->file('photo'));
                $updateUser['photo'] = $filename;
            }

            $this->User->where('id', $userId)
                ->update($updateUser);

            $user->prefixname = $request['prefixname'];
            $user->firstname = $request['firstname'];
            $user->middlename = $request['middlename'];
            $user->lastname = $request['lastname'];
            $user->suffixname = $request['suffixname'];
            $user->email = $request['email'];
            $user->photo = $filename ?? $user->photo;
        }

        return view('users.edit', ['user' => $user]);
    }

    /**
     * for soft delete of user
     * @param Request $request
     *
     * @return view
     */
    public function destroy(Request $request) {
        $user = $this->getSingleUser($request);
        $userId = $user->id;

        if (is_null($user)) {
            return redirect('/users/index');
        }
        $this->User->where('id', $userId)
            ->update(['deleted_at' => date('Y-m-d H:i:s')]);

        return redirect('/users/show/' . $user->id);
    }

    /**
    * for showing single user
    * @param Request $request
    *
    * @return view
    */
    public function show(Request $request) {
        $user = $this->getSingleUser($request);

        if (is_null($user)) {
            return redirect('/users/index');
        }

        return view('users.show', ['user' => $user]);
    }

    /**
    * for showing trashed users
    *
    * @return view
    */
    public function trashed() {
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
            'type',
            'deleted_at'
        )->onlyTrashed()
        ->get();

        return view('users.trashed', ['users' => $users]);
    }

    
    /**
     * for restoring soft deleted of user
     * @param UserRequest $request
     *
     * @return redirect
     */
    public function restore(UserRequest $request) {
        $user = $this->getSingleUser($request);
        $userId = $user->id;

        if (is_null($user)) {
            return redirect('/users/index');
        }

        $this->User->where('id', $userId)
            ->update(['deleted_at' => null]);

        return redirect('/users/index');
    }

    /**
     * for hard delete
     *
     * @return redirect
     */
    public function delete(Request $request) {
        User::withTrashed()
            ->where('id', $request->route('id'))
            ->forceDelete();

        return redirect('/users/index');
    }

    public function store(UserRequest $userRequest) {

    }

    public function update(UserRequest $userRequest, int $id) {

    }
}
