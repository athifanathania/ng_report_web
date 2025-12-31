<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'photos' => 'array',
        'input_date' => 'date',
        'email_sent_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    // DMC-style thumbnail getter
    public function getThumbnailAttribute()
    {
        return is_array($this->photos) && count($this->photos)
            ? $this->photos[0]
            : null;
    }
}
