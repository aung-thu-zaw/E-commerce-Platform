<?php

namespace App\Models;

use App\Notifications\AccountRegistered\VerifyEmailQueued;
use App\Notifications\ResetPasswordQueued;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Overtrue\LaravelFollow\Traits\Followable;
use Overtrue\LaravelFollow\Traits\Follower;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use Searchable;
    use Follower;
    use Followable;
    use HasRoles;
    use HasPermissions;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     *     @return array<string|null>
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'shop_name' => $this->shop_name,
        ];
    }

    /**
     * @param  array<string>  $filterBy
     * @param  Builder<User>  $query
     */
    public function scopeFilterBy(Builder $query, array $filterBy): void
    {
        $query->when(
            $filterBy['search'] ?? null,
            function ($query, $search) {
                $query->where(
                    function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%'.$search.'%')
                        ->orWhere('shop_name', 'LIKE', '%'.$search.'%');
                    }
                );
            }
        );

        $query->when($filterBy['created_from'] ?? null, function ($query, $createdFrom) {
            $query->whereDate('created_at', '>=', $createdFrom);
        })
        ->when($filterBy['created_until'] ?? null, function ($query, $createdUntil) {
            $query->whereDate('created_at', '<=', $createdUntil);
        })
        ->when($filterBy['deleted_from'] ?? null, function ($query, $deletedFrom) {
            $query->whereDate('deleted_at', '>=', $deletedFrom);
        })
        ->when($filterBy['deleted_until'] ?? null, function ($query, $deletedUntil) {
            $query->whereDate('deleted_at', '<=', $deletedUntil);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<User, never>
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::needsRehash($value) ? bcrypt($value) : $value,
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<User, never>
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_starts_with($value, 'http') ? $value : asset("storage/avatars/$value"),
            get: fn ($value) => $value ?? (auth()->user() ? asset('storage/avatars/default-avatar-'.auth()->user()->id.'.png') : asset('storage/avatars/default-avatar.png')),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Cart>
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Watchlist>
     */
    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Order>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Coupon>
     */
    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class)
            ->withPivot('used_at')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<DeliveryInformation>
     */
    public function deliveryInformation(): HasOne
    {
        return $this->hasOne(DeliveryInformation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ProductQuestion>
     */
    public function productQuestions(): HasMany
    {
        return $this->hasMany(ProductQuestion::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Conversation>
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Product>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ShopReview>
     */
    public function shopReviews(): HasMany
    {
        return $this->hasMany(ShopReview::class, 'shop_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Message>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    // /**
    // * @return \Illuminate\Database\Eloquent\Relations\HasMany<Message>
    // */
    // public function liveChatMessages(): HasMany
    // {
    //     return $this->hasMany(Message::class);
    // }

    public function getRedirectRouteName(): string
    {
        return match ((string) $this->role) {
            'admin' => 'admin.dashboard',
            'seller' => 'seller.dashboard',
            'user' => 'home',
        };
    }

    public function logoutRedirect(): string
    {
        return match ((string) $this->role) {
            'admin' => 'admin.login',
            'seller' => 'seller.login',
            'user' => 'home',
        };
    }

    public static function deleteDefaultAvatar(User $user): void
    {
        if (file_exists(storage_path('app/public/avatars/default-avatar-'.$user->id.'.png'))) {
            unlink(storage_path('app/public/avatars/default-avatar-'.$user->id.'.png'));
        }
    }

    public static function deleteUserAvatar(string $avatar): void
    {
        if (! empty($avatar) && file_exists(storage_path('app/public/avatars/'.pathinfo($avatar, PATHINFO_BASENAME)))) {
            unlink(storage_path('app/public/avatars/'.pathinfo($avatar, PATHINFO_BASENAME)));
        }
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailQueued());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordQueued($token));
    }
}
