<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliator extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get all of the Affiliates for the Affiliator
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Affiliates(): HasMany
    {
        return $this->hasMany(Affiliate::class);
    }

    /**
     * Get all of the totalAffiliates for the Affiliator
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function totalAffiliates(): HasMany
    {
        return $this->hasMany(TotalAffiliate::class);
    }
}
