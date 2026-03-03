<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'name_uz',
        'description',
        'description_uz',
        'slug',
        'category_id',
        'price',
        'discount_percent',
        'is_active',
        'manufacturer',
        'article',
        'model',
        'coating',
        'index',
        'sph',
        'cyl',
        'axis',
        'family',
        'color',
        'option',
    ];

    protected $appends = ['localized_name', 'localized_description'];

    public function getLocalizedNameAttribute()
    {
        return app()->getLocale() === 'uz' && $this->name_uz
            ? $this->name_uz
            : $this->name;
    }

    public function getLocalizedDescriptionAttribute()
    {
        return app()->getLocale() === 'uz' && $this->description_uz
            ? $this->description_uz
            : $this->description;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $baseSlug = Str::slug($product->name, '-');
                $slug = $baseSlug;
                $count = 1;
                while (self::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$count}";
                    $count++;
                }
                $product->slug = $slug;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')->withPivot('value');
    }
}
