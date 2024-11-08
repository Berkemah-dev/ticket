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
        * Get the affiliate that owns the TotalAffiliate
        *
        * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Get the order that owns the TotalAffiliate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
