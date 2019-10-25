<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Image\Manipulations;
use App\Traits\Uuids;

class User extends Authenticatable implements HasMedia
{
    use Uuids, Notifiable, HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'username', 'name', 'email', 'password', 'lang',
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
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->school->email;
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
}
