<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisionAgent extends Model
{
    use HasFactory;


    protected $fillable = [
        "supervision_id", "agent_id", "photo", "comment"
    ];

    public function notes() { 
        return $this->hasMany(SupervisionAgentNote::class, "supervision_agent_id", "id");
    }


    public function agent() { 
        return $this->belongsTo(Agent::class, "agent_id"); 
    }

}
