<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'serviceId',
    ];
}
