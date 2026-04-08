<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GuideCategory extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'color',
        'sort_order',
    ];

    /**
     * Автоматически заполняет slug при создании категории.
     */
    protected static function booted(): void
    {
        static::creating(function (GuideCategory $model): void {
            if ($model->slug === null || $model->slug === '') {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Гайды, относящиеся к категории.
     */
    public function guides(): HasMany
    {
        return $this->hasMany(Guide::class);
    }

    /**
     * Категории для селектов, отсортированные по порядку.
     */
    public static function forSelect(): Collection
    {
        return static::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }
}
