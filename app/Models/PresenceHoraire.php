<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PresenceHoraire extends Model
{
    use HasFactory;


    protected $table = "presence_horaires";


    protected $primaryKey = 'id';



    protected $casts = [
        "started_at"=>"datetime:H:i",
        "ended_at"=>"datetime:H:i"
    ];


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        "libelle","started_at","ended_at", "tolerence"
    ];


    public function agents(): HasMany{
        return $this->hasMany(Agent::class, foreignKey:"horaire_id", localKey:'id');
    }

}
