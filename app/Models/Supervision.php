<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    use HasFactory;

    protected $fillable = [
        "supervisor_id",
        "site_id",
        "started_at",
        "ended_at",
        "general_comment",
        "photo_debut",
        "photo_fin",
        "latlng",
        "distance"
    ];

    protected $casts = [
        'started_at'=>'datetime:d/m/Y H:i',
        'ended_at'=>'datetime:d/m/Y H:i'
    ];


    public function supervisor() { 
        return $this->belongsTo( Agent::class, 'supervisor_id');
    }


    public function site() { 
        return $this->belongsTo(Site::class, "site_id");
    }
    public function agents() { 
        return $this->hasMany(SupervisionAgent::class, "supervision_id", "id"); 
    }
    public function patrols() { 
        return $this->hasMany(Patrol::class, 'supervision_id', "id");
    }
}
