<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $guarded = [];

    public function defaultSupplier()
    {
        return $this->belongsTo(Supplier::class, 'default_supplier_id');
    }

    public function ngReports()
    {
        return $this->hasMany(NgReport::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
