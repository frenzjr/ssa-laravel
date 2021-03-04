<?php

namespace App\Services;

use App\Detail;
use App\User;
use App\Services\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
    /**
     * The model instance.
     *
     * @var App\User
     */
    protected $model;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Constructor to bind model to a repository.
     *
     * @param \App\User                $model
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;;
    }

    /**
     * Define the validation rules for the model.
     *
     * @param  int $id
     * @return array
     */
    public function rules($id = null)
    {
        return [
            /**
             * Rule syntax:
             *  'column' => 'validation1|validation2'
             *
             *  or
             *
             *  'column' => ['validation1', function1()]
             */
            'id' => 'nullable',
            'prefixname' => ['nullable', function($value) {
                return in_array($value, ['Mr', 'Mrs', 'Ms']);
            }],
            'firstname' => 'required',
            'middlename' => 'nullable',
            'lastname' => 'required',
            'username' => 'required|unique:users|max:250|string',
            'email' => 'required|email|unique:users', 
            'password' => 'required|email', 
        ];
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list()
    {
        $users = User::all();

        return new LengthAwarePaginator($users, $users->count(), 5);
    }

    /**
     * Create model resource.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes)
    {
        return User::create($attributes);
    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id):? Model
    {
        return User::find($id);
    }

    /**
     * Update model resource.
     *
     * @param  integer $id
     * @param  array   $attributes
     * @return boolean
     */
    public function update(int $id, array $attributes): bool
    {
        return $this->User->where('id', $id)
            ->update($attributes);
    }

    /**
     * Soft delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function destroy($id)
    {
        return $this->User->where('id', $id)
            ->update(['deleted_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listTrashed()
    {
        return User::select(
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
    }

    /**
     * Restore model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function restore($id)
    {
        return $this->User->where('id', $id)
            ->update(['deleted_at' => null]);
    }

    /**
     * Permanently delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function delete($id)
    {
        return User::withTrashed()
            ->where('id', $id)
            ->forceDelete();
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string
    {
        return Hash::make($key);
    }

    /**
     * Upload the given file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public function upload(UploadedFile $file)
    {
        return Storage::disk('public')->put('users', $request->file('photo'));
    }

    /**
     * saveUserDetail saving of user detail
     * @param oject $attribute
     *
     * @return null
     */
    public function saveUserDetail($attribute) {
        $middleName = substr($attribute->middlename, 0, 1);
        $middleName = strlen($middleName) == 1 ? $middleName . '.' : '';
        $fullname = implode(' ', [$attribute->firstname, $middleName, $attribute->lastname, $attribute->suffixname]);
        $avatar = $attribute->photo;
        $gender = $attribute->prefixname ==='Mr' ? 'Male' : 'Female';
        
        
        $toSave = [
            [
                new Detail([
                    'key' => 'Full name',
                    'value' => $fullname,
                    'user_id' => $attribute->id,
                    'type' => 'bio'
                ])
            ], [
                new Detail([
                    'key' => 'Middle Initial',
                    'value' => $middleName,
                    'user_id' => $attribute->id,
                    'type' => 'bio'
                ])
            ], [
                new Detail([
                    'key' => 'Avatar',
                    'value' => $avatar,
                    'user_id' => $attribute->id,
                    'type' => 'bio'
                ])
            ], [
                new Detail([
                    'key' => 'Gender',
                    'value' => $gender,
                    'user_id' => $attribute->id,
                    'type' => 'bio'
                ])
            ]
        ];

        Detail::saveMany($toSave);
    }
}
