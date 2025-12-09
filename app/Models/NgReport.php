<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'photos'       => 'array',    // karena json
        'input_date'   => 'date',
        'email_sent_at'=> 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
