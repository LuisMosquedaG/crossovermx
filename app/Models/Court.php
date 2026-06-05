<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'surface_type',
        'image_path',
        'client_id',        
    ];
    
    public function schedules()
    {
        return $this->hasMany(CourtSchedule::class);
    }
}