<?php

namespace App\Models;

use App\Enums\GuideCategory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guide extends Model
{
    use HasUlids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'author_name',
        'title',
        'category',
        'description',
        'tags',
        'steps',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => GuideCategory::class,
            'tags' => 'array',
            'steps' => 'array',
        ];
    }

    /**
     * Автор гайда.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
