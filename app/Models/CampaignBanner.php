<?php

namespace App\Models;

use App\Models\Scopes\FilteredByDateScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class CampaignBanner extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    protected $guarded = [];

    /**
     *     @return array<string|bool>
     */
    public function toSearchableArray(): array
    {
        return [
            'url' => $this->url,
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
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<CampaignBanner, never>
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date('j-F-Y', strtotime($value)),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<CampaignBanner, never>
     */
    protected function deletedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date('j-F-Y', strtotime($value)),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<CampaignBanner, never>
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_starts_with($value, 'http') ? $value : asset("storage/campaign-banners/$value"),
        );
    }

    public static function deleteImage(string $campaignBannerImage): void
    {
        if (! empty($campaignBannerImage) && file_exists(storage_path('app/public/campaign-banners/'.pathinfo($campaignBannerImage, PATHINFO_BASENAME)))) {
            unlink(storage_path('app/public/campaign-banners/'.pathinfo($campaignBannerImage, PATHINFO_BASENAME)));
        }
    }
}
