<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    /**
     * Get the user that owns the website.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the domains for the website.
     */
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * Get the primary domain.
     */
    public function primaryDomain()
    {
        return $this->hasOne(Domain::class)->where('is_primary', true);
    }

    /**
     * Get the nginx conf file path.
     */
    public function getConfPathAttribute()
    {
        return "/www/vhost/{$this->id}.conf";
    }

    /**
     * Get the website files directory path.
     */
    public function getFilesPathAttribute()
    {
        return "/www/hostfiles/{$this->id}";
    }
}
