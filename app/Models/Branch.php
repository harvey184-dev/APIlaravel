<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $collection = 'branches';
    protected $fillable = [
        'tokenbranch',
        'branchname',
        'branchaddress',
        'branchphone',
        'branchbusinesslicense',
        'organizationsID'
    ];
}
