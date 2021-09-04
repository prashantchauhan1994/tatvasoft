<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'start_date','end_date','recurrence_type','recurrence_at'
    ];

    public function events()
    {
        return $this->hasMany(EventList::class);
    }
}
