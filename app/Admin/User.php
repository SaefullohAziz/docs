<?php

namespace App\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Image\Manipulations;

class User extends Authenticatable implements HasMedia
{
	use Notifiable, HasRoles, HasMediaTrait;

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
     * Get the log for the staff.
     */
    public function log()
    {
        return $this->hasMany('App\ActivityLog', 'log_id')->where('created_by', 'admin');
    }

    /**
     * Get the school comment for the staff.
     */
    public function schoolComment()
    {
        return $this->hasMany('App\SchoolComment');
    }

    /**
     * Register media collection
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('photos')
            // ->withFallbackUrl('/img/avatar/default.png')
            // ->withFallbackPath(public_path('/img/avatar/default.png'))
            ->singleFile();
    }

    /**
     * Register media conversion
     * 
     * @param \Spatie\MediaLibrary\Models\Media $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('avatar')
            ->fit(Manipulations::FIT_CROP, 150, 150)
            ->optimize();
    }

    /**
     * Get the user's avatar.
     *
     * @param  string  $value
     * @return string
     */
    public function getAvatarAttribute()
    {
        if ($this->getMedia('photos')->count() > 0) {
            return $this->getFirstMediaUrl('photos', 'avatar');
        }
        return '/img/avatar/default.png';
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
