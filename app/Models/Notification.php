<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'nom_superviseur',
        'matricule',
        'station',
        'photo',
        'heure_action',
        'is_read',
    ];
}
