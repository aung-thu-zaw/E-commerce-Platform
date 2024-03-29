<?php

namespace App\Models;

use App\Models\Scopes\FilteredByDateScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class SellerProductBanner extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    protected $guarded = [];

    /**
     *     @return array<string>
     */
    public function toSearchableArray(): array
    {
        return [
            'url' => $this->url,
            'status' => $this->status,
        ];
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new FilteredByDateScope());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<SellerProductBanner, never>
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date('j-F-Y', strtotime($value)),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<SellerProductBanner, never>
     */
    protected function deletedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date('j-F-Y', strtotime($value)),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<SellerProductBanner, never>
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_starts_with($value, 'http') ? $value : asset("storage/product-banners/$value"),
        );
    }

    public static function deleteImage(string $sellerProductBannerImage): void
    {
        if (! empty($sellerProductBannerImage) && file_exists(storage_path('app/public/product-banners/'.pathinfo($sellerProductBannerImage, PATHINFO_BASENAME)))) {
            unlink(storage_path('app/public/product-banners/'.pathinfo($sellerProductBannerImage, PATHINFO_BASENAME)));
        }
    }
}
