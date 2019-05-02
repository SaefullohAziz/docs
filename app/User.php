<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * Get the school that owns the user.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
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
}
