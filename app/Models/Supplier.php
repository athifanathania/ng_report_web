<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    public function parts()
    {
        return $this->hasMany(Part::class, 'default_supplier_id');
    }

    public function ngReports()
    {
        return $this->hasMany(NgReport::class);
    }
}
