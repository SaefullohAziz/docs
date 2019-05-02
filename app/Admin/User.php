<?php

namespace App\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
	use Notifiable, HasRoles;

    protected $table = 'staffs';
    protected $guard = 'admin';
	protected $guard_name = 'admin';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password',
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
     * Get the school comment for the staff.
     */
    public function schoolComment()
    {
        return $this->hasMany('App\SchoolComment');
    }

    /**
     * Rules for form validation
     * 
     * @param  string $type Type of form. Create or edit.
     */
    public static function rules($type = null)
    {
        $rules = [
            'username' => [
                'required',
                'unique:users,username'
            ],
            'name' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required',
            ],
            'password_confirmation' => [
                'required',
                'same:password'
            ],
        ];

        if ($type == 'update') {
            $rules['username'] = ['required'];
            $rules['email'] = ['required', 'email'];
            $rules['password'] = [];
            $rules['password_confirmation'] = [];
        }

        return $rules;
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('staffs');
    }

    /**
     * Show user list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::get($request)->select('*');
    }
}
