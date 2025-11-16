<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'domain',
        'is_primary',
    ];

    protected $casts = [
        'website_id' => 'integer',
        'is_primary' => 'boolean',
    ];

    /**
     * Get the website that owns the domain.
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
