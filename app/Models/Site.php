<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;


     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sites';

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
        "name",
        "code",
        "latlng",
        "adresse",
        "phone",
        "agency_id",
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
     * Belongs to Agency
     * @return BelongsTo
    */
    public function agencie() : BelongsTo{
        return $this->belongsTo(Agencie::class, foreignKey:"agency_id");
    }

    /**
     * has menu areas
     * @return HasMany
     * */
    public function areas() : HasMany{
        return $this->hasMany(Area::class, foreignKey: 'site_id', localKey: "id");
    }

}