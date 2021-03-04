<?php

namespace App;

use App\Events\UserSaved;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'lastname',
        'suffixname',
        'username',
        'email',
        'photo',
        'type',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * validor
     * @param array $data
     *
     * @return Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'prefixname' => ['nullable', 'string', 'max:3'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
    }

    /**
     * getAvatarAttribute for getting the photo of the user
     * @param int $id user id
     *
     * @return string photo path
     */
    protected static function getAvatarAttribute(int $id) {
        return DB::table('users')
            ->find($id)
            ->photo;
    }

    /**
     * getFullnameAttribute for getting the fullname of the user
     * @param int $id user id
     *
     * @return string fullname of the user
     */
    protected static function getFullnameAttribute(int $id) {
        $user = DB::table('users')->find($id);
        $middleName = substr($user->middlename, 0, 1);
        $middleName = strlen($middleName) == 1 ? $middleName . '.' : '';

        return implode(' ', [$user->firstname, $middleName, $user->lastname, $user->suffixname]);
    }

    /**
     * getMiddleInitialAttribute for getting the middle initial of the user
     * @param int $id user id
     *
     * @return string middle initial
     */
    protected static function getMiddleInitialAttribute(int $id) {
        $user = DB::table('users')->find($id);
        $middleName = substr($user->middlename, 0, 1);

        return strlen($middleName) == 1 ? $middleName . '.' : '';
    }

    public function details() {
        return $this->hasMany(Detail::class);
    }

    /**
     * save event of the user model
     * @param array $options
     *
     * @return User::class
     */
    // public function save(array $options = []) {

    // }
}
