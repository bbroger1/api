<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

//composer require tymon/jwt-auth:dev-develop --prefer-source
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*public function create($fields)
    {
        return parent::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);
    }*/

    public function login($credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new \Exception('Credenciais incorretas, verifique-as e tente novamente.', -404);
        }
        return $token;
    }

    public function logout($token)
    {
        if (!JWTAuth::invalidate($token)) {
            throw new \Exception('Erro. Tente novamente.', -404);
        }
    }

    public function tasklist()
    {
        return $this->hasMany(TaskList::class);
    }

    public function task()
    {
        return $this->hasMany(Tasks::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
