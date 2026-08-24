<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Investment extends Model
{
    use HasFactory;

    public const RISK_LEVELS = ['low', 'moderate', 'high', 'variable'];

    protected $fillable = [
        'investment_category_id', 'name', 'slug', 'short_description', 'description',
        'risk_level', 'risk_description', 'liquidity_description', 'profitability_description',
        'taxation_description', 'protection_description', 'advantages', 'points_of_attention',
        'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(InvestmentCategory::class, 'investment_category_id');
    }

    public function sources(): BelongsToMany
    {
        return $this->belongsToMany(Source::class)
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function riskLabel(): string
    {
        return match ($this->risk_level) {
            'low' => 'Baixo',
            'moderate' => 'Moderado',
            'high' => 'Alto',
            default => 'Variável',
        };
    }
}
