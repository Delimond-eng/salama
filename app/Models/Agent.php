<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agent extends Model
{
    use HasFactory;

     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'agents';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        "photo",
        "matricule",
        "fullname",
        "password",
        "role",
        "agency_id",
        "site_id",
        "groupe_id",
        "status"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'=>'datetime:d/m/Y H:i',
        'updated_at'=>'datetime:d/m/Y H:i'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];


    /**
     * Agency Belongs to site
     * @return BelongsTo
    */
    public function site() : BelongsTo{
        return $this->belongsTo(Site::class, foreignKey:"site_id",);
    }



    /**
     * Belongs to agency
     * @return BelongsTo
    */
    public function agencie() : BelongsTo{
        return $this->belongsTo(Agencie::class, foreignKey:"agency_id",);
    }

    /**
     * Belongs to agency
     * @return BelongsTo
    */
    public function groupe() : BelongsTo{
        return $this->belongsTo(AgentGroup::class, foreignKey:"groupe_id",);
    }


    public function stories(){
        return $this->hasMany(AgentHistory::class, "agent_id", "id");
    }

    public function plannings()
    {
        return $this->hasMany(AgentGroupPlanning::class, "agent_id", "id");
    }
}
