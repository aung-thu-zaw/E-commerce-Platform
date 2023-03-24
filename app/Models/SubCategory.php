<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class SubCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use HasSlug;

    protected $guarded=[];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }


    /**
    *     @return array<string>
    */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'status' => $this->status,
        ];
    }


    /**
    * @return \Illuminate\Database\Eloquent\Casts\Attribute<SubCategory, never>
    */
    protected function image(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_starts_with($value, "http") ? $value : asset("storage/subCategories/$value"),
        );
    }


    /**
    * @return \Illuminate\Database\Eloquent\Casts\Attribute<SubCategory, never>
    */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date("Y/m/d", strtotime($value)),
        );
    }



    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    public static function deleteImage(object $subCategory): void
    {
        if (!empty($subCategory->image) && file_exists(storage_path("app/public/subCategories/$subCategory->image"))) {
            unlink(storage_path("app/public/subCategories/$subCategory->image"));
        }
    }
}