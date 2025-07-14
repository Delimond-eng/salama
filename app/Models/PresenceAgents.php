<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceAgents extends Model
{
    use HasFactory;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent_id', 'site_id', 'gps_site_id', 'horaire_id',
        'started_at', 'ended_at', 'duree', 'retard',
        'photos_debut', 'photos_fin',
        'status_photo_debut', 'status_photo_fin',
        'commentaires', 'status',
        'date_reference'
    ];

    protected $casts =[
        "created_at"=>"date:d/m/y",
        "started_at"=>"datetime:H:i",
        "ended_at"=>"datetime:H:i"
    ];
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function horaire()
    {
        return $this->belongsTo(PresenceHoraire::class, 'horaire_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'gps_site_id');
    }

}
