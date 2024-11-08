<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event', 'id');
    }

    /**
     * Get all of the totalAffiliates for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function totalAffiliates(): HasMany
    {
        return $this->hasMany(TotalAffiliate::class);
    }
}
