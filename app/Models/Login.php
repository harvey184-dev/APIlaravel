<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Login extends Model
{
    use HasFactory;

    protected $collection = 'logins';
    protected $fillable = ['cccd', 'typeusers', 'password'];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
