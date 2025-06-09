<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'agency_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function agencie(){
        return $this->belongsTo(Agencie::class, foreignKey: 'agency_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function hasPermission($menuSlug, $actionSlug)
    {
        return $this->permissions()->whereHas('menu', fn($q) => $q->where('slug', $menuSlug))
                                ->whereHas('action', fn($q) => $q->where('slug', $actionSlug))
                                ->exists();
    }

    public function hasMenu($menuSlug){
        return $this->permissions()->whereHas('menu', fn($q) => $q->where('slug', $menuSlug))->exists();
    }
}
