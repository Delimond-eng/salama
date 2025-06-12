<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresenceSupervisorSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'started_at',
        'ended_at',
        'start_photo',
        'end_photo',
        'latlng',
        'distance',
        'comment',
        'schedule_id',
        'site_id',
        'agent_id',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'=>'datetime:d/m/Y H:i',
        'updated_at'=>'datetime:d/m/Y H:i',
        'date'=>'datetime:d/m/Y',
        'started_at'=>'datetime:H:i',
        'ended_at'=>'datetime:H:i',
    ];

    /**
     * Planning superviseur auquel ce site appartient.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ScheduleSupervisor::class, 'schedule_id');
    }

    /**
     * Site concerné par ce planning.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }


    /**
     * Agent concerné par cette presence.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}
