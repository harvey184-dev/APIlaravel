<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $collection = 'users';
    protected $fillable = [
        'fullname',
        'address',
        'organizationalvalue',
        'phone',
        'imgidentification',
        'cccd',
        'tokenuser',
        'organizationsID',
        'branchID'
    ];
}
