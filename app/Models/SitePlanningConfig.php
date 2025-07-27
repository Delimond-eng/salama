<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitePlanningConfig extends Model
{
    use HasFactory;


    protected $fillable = [
        'site_id',
        'start_hour',
        'interval',
        'pause',
        'number_of_plannings',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public static function getForSite($siteId)
    {
        return self::where('site_id', $siteId)->first();
    }
}
