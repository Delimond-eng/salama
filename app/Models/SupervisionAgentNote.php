<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisionAgentNote extends Model
{
    use HasFactory;


    protected $fillable = [
        "supervision_agent_id",
        "control_element_id",
        "note",
        "comment"
    ];


    public function element() { 
        return $this->belongsTo(SupervisionControlElement::class, 'control_element_id'); 
    }
}
