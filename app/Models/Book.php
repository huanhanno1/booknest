<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title', 'slug', 'author', 'publisher', 'publish_year',
        'description', 'price', 'sale_price', 'stock', 'cover_image', 'isbn',
        'pages', 'is_active', 'is_featured', 'sold_count', 'view_count',
    ];

    protected $casts = [
        'price' => 'decimal:0',
        'sale_price' => 'decimal:0',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($book) {
            if (empty($book->slug)) {
                $book->slug = Str::slug($book->title);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . 'đ';
    }

    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return null;
        }
        if (str_starts_with($this->cover_image, 'http')) {
            return $this->cover_image;
        }
        if (str_starts_with($this->cover_image, '/')) {
            return asset($this->cover_image);
        }
        return asset('storage/' . $this->cover_image);
    }

    public function getFormattedSalePriceAttribute()
    {
        if ($this->sale_price) {
            return number_format($this->sale_price, 0, ',', '.') . 'đ';
        }
        return null;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestSelling($query)
    {
        return $query->orderBy('sold_count', 'desc');
    }
}
