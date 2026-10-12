<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock',
        'image',
        'gender',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $query, string $search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('brand', fn (Builder $q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['brand'] ?? null, fn (Builder $query, $slug) => $query->whereHas(
                'brand', fn (Builder $q) => $q->where('slug', $slug)
            ))
            ->when($filters['category'] ?? null, fn (Builder $query, $slug) => $query->whereHas(
                'category', fn (Builder $q) => $q->where('slug', $slug)
            ))
            ->when($filters['gender'] ?? null, fn (Builder $query, $gender) => $query->where('gender', $gender))
            ->when($filters['min_price'] ?? null, fn (Builder $query, $min) => $query->where('price', '>=', $min))
            ->when($filters['max_price'] ?? null, fn (Builder $query, $max) => $query->where('price', '<=', $max))
            ->when($filters['sort'] ?? null, function (Builder $query, string $sort) {
                match ($sort) {
                    'price_low' => $query->orderBy('price'),
                    'price_high' => $query->orderByDesc('price'),
                    'name' => $query->orderBy('name'),
                    default => $query->latest(),
                };
            }, fn (Builder $query) => $query->latest());
    }

    public function getDisplayPriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price);
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return Str::startsWith($this->image, ['http://', 'https://'])
                ? $this->image
                : (Str::startsWith($this->image, '/') ? asset($this->image) : asset('storage/'.$this->image));
        }

        return 'https://placehold.co/600x600?text='.urlencode($this->name);
    }
}
