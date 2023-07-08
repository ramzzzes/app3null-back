<?php

namespace App\Models;

use App\Jobs\SendVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country',
        'birthdate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verification_hash',
        'email_verified_at',
        'birthdate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
    ];

    public function Country(): HasOne
    {
        return $this->hasOne(Country::class,'id','country');
    }

    public static function boot(): void
    {
        parent::boot();

        self::creating(function($model){
            $model->email_verification_hash = sha1($model['email'].mt_rand());
        });

        self::created(function($model){
            dispatch(new SendVerifyEmail($model));
        });
    }

    public function verifyEmail($code): void
    {
        $checkCode = self::query()
            ->where('email_verification_hash',$code)
            ->whereNull('email_verified_at');

        if($checkCode->count() === 0){
            throw new \Exception('Incorrect Code ');
        }

        $checkCode->update([
            'email_verification_hash' => null,
            'email_verified_at' => now()
        ]);
    }

    protected function birthdate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d.m.y'),
        );
    }


}
