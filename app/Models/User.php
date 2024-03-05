<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\RoleType;
use App\Notifications\Customer\ForgotPasswordCustomerNotification;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;


#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    /**
     * @return string
     */
    public function getRoleType(): string
    {
        return RoleType::getRoleValue(roles: $this->getRoleNames());
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return RoleType::getRoleName(roles: $this->getRoleNames());
    }


    /**
     * @return string
     */
    public function since(): string
    {
        return $this->created_at->format('Y-m-d');
    }


    /**
     * Send a password reset notification to the user.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ForgotPasswordCustomerNotification(token: $token));
    }
}
