<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TotalAffiliate extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the affiliator that owns the TotalAffiliate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affiliator(): BelongsTo
    {
        return $this->belongsTo(Affiliator::class);
    }

    /**
     * Get all of the affiliate for the TotalAffiliate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function affiliate(): HasMany
    {
        return $this->hasMany(Affiliate::class);
    }
}
