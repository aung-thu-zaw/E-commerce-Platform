<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<ProductQuestion, never>
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date('j/F/Y h:i:s A', strtotime($value)),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User,ProductQuestion>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User,ProductQuestion>
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Product,ProductQuestion>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<ProductAnswer>
     */
    public function productAnswer(): HasOne
    {
        return $this->hasOne(ProductAnswer::class);
    }
}
