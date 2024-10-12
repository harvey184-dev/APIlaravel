<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID',
        'organizationID',
        'result',
        'namePtient',
        'cccdPatient',
        'birthdayPatient',
        'sexPatient',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'medicalRecordID');
    }

    public function medications()
    {
        return $this->hasMany(Medication::class, 'medicalRecordID');
    }
}
