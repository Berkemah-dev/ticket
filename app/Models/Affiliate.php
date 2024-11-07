<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the affiliator that owns the Affiliate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affiliator(): BelongsTo
    {
        return $this->belongsTo(Affiliator::class);
    }

    /**
     * Get all of the totalAffiliates for the Affiliate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function totalAffiliates(): HasMany
    {
        return $this->hasMany(TotalAffiliate::class);
    }
}
