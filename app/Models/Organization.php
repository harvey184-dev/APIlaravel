<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $collection = 'organizations';
    protected $fillable = [
        'nameorg',
        'nameadmin',
        'addressadmin',
        'emailadmin',
        'phoneadmin',
        'businessBase64',
        'tokenorg',
        'statusorg'
    ];
}
