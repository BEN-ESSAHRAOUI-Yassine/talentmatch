<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'required_skills',
        'minimum_experience',
    ];

    protected function casts(): array
    {
        return [
            'required_skills' => 'array',
            'minimum_experience' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function analyses(): HasMany
    {
        return $this->hasMany(Analyse::class);
    }

    public function candidats(): HasManyThrough
    {
        return $this->hasManyThrough(
            Candidat::class,
            Analyse::class,
            'offre_id',
            'id',
            'id',
            'candidat_id',
        );
    }
}
