<?php

namespace App\Models;

use App\Models\Scopes\FilteredByDateScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class WebsiteFeedback extends Model
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
            'email' => $this->email,
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
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<WebsiteFeedback, never>
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date('j-F-Y ( h:i A )', strtotime($value)),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<WebsiteFeedback, never>
     */
    protected function deletedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date('j-F-Y ( h:i A )', strtotime($value)),
        );
    }
}
