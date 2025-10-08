<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisionPatrol extends Model
{
    use HasFactory;

    protected $fillable = [
        "supervision_id",
        "patrol_id"
    ];

    public function patrol(){
        return $this->belongsTo(Patrol::class, "patrol_id");
    }
    public function supervision(){
        return $this->belongsTo(Supervision::class, "supervision_id");
    }
}
