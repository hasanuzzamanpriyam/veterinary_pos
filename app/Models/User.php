<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'banner_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'banner_photo_url',
    ];

    /**
     * Get the URL to the user's banner photo.
     *
     * @return string
     */
    public function getBannerPhotoUrlAttribute()
    {
        return $this->banner_photo_path
            ? asset('storage/' . $this->banner_photo_path)
            : null;
    }

    /**
     * Update the user's banner photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateBannerPhoto($photo)
    {
        tap($this->banner_photo_path, function ($previous) use ($photo) {
            $this->forceFill([
                'banner_photo_path' => $photo->storePublicly('banner-photos', ['disk' => 'public']),
            ])->save();

            if ($previous) {
                Storage::disk('public')->delete($previous);
            }
        });
    }

    /**
     * Delete the user's banner photo.
     *
     * @return void
     */
    public function deleteBannerPhoto()
    {
        if (is_null($this->banner_photo_path)) {
            return;
        }

        Storage::disk('public')->delete($this->banner_photo_path);

        $this->forceFill([
            'banner_photo_path' => null,
        ])->save();
    }
}
