<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @SWG\Definition(
 *      definition="User",
 *      required={"name", "phone", "address", "gender", "age", "email", "password"},
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="gender",
 *          description="gender",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="age",
 *          description="age",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email_verified_at",
 *          description="email_verified_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="remember_token",
 *          description="remember_token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, Notifiable, SoftDeletes, InteractsWithMedia, LaratrustUserTrait;

    public $table = 'users';

    protected $dates = ['deleted_at'];

    public $guard = [];

    public $fillable = [
        'name',
        'phone',
        'address',
        'gender',
        'age',
        'image',
        'email',
        'password'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'phone' => 'string',
        'address' => 'string',
        'gender' => 'string',
        'age' => 'integer',
        'image' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'gender' => 'required',
        'age' => 'required',
        'image' => 'nullable',
        'email' => 'required|email|unique:users,email',
        'password' => 'required'
    ];

    public function setPasswordAttribute($password)
    {
        if (is_null($password) && strlen($password) == 0)
            return;
        if (!is_null($password) && strlen($password) == 60 && preg_match('/^\$2y\$/', $password)) {
            $this->attributes['password'] = $password ?? '';
        } else {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/assets/images/user.jpg');
    }

    /* Accessors */
    public function getImageAttribute()
    {
        return url($this->getFirstMediaUrl('image'));
    }

    /**
     * Get the user's Devicetokens.
     */
    public function devicetokens()
    {
        return $this->hasMany(\App\Models\Devicetoken::class);
    }

}
