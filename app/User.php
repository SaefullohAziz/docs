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

    /**
     * Get the user's school status.
     *
     * @param  string  $value
     * @return string
     */
    public function getStatusAttribute()
    {
        return $this->school->statusUpdate->status;
    }

    /**
     * Determine user's school level
     */
    public function hasLevel($levels)
    {
        if ( ! is_array($levels)) {
            $levels = explode(',', str_replace(', ', ',', $levels));
        }
        if (in_array($this->school->statusUpdate->status->level->name, $levels)) {
            return true;
        }
        return false;
    }

    /**
     * Determine user's school status
     */
    public function hasStatus($statuses)
    {
        if ( ! is_array($statuses)) {
            $statuses = explode(',', str_replace(', ', ',', $statuses));
        }
        if (in_array($this->school->statusUpdate->status->order_by, $statuses)) {
            return true;
        }
        return false;
    }
}
