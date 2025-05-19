<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasFactory, Notifiable;

    protected $fillable = [//this is also to specify the the fillabbles of the user
        'name',
        'email',
        'password',
    ];


    protected $hidden = [//these are the data that have to be hidden
        'password',
        'remember_token',
    ];

//this is to provide additional data 
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);//here one user can 
        // have many products
    }
}
