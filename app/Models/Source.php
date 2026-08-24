<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution', 'title', 'url', 'publication_date', 'accessed_at',
    ];

    protected function casts(): array
    {
        return [
            'publication_date' => 'date',
            'accessed_at' => 'date',
        ];
    }

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class)
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function investments(): BelongsToMany
    {
        return $this->belongsToMany(Investment::class)
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }
}
