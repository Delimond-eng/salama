<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisionControlElement extends Model
{
    use HasFactory;


    protected $fillable = [
        'libelle',
        'description',
        'active',
    ];


    protected $hidden = [
        "created_at","updated_at"
    ];

    public function controls()
    {
        return $this->hasMany(PresenceSupervisorControl::class, 'element_id');
    }

}
