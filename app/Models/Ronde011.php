<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ronde011 extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'agent_id',
        'comment',
        'photo',
        'latlng',
        'distance',
        'created_at'
    ];

    protected $casts=[
        'created_at' => 'datetime:d/m/Y H:i'
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, "site_id");
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class, "agent_id");
    }

}
