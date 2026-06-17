<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Candidat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cv_text',
    ];

    public function analyse(): HasOne
    {
        return $this->hasOne(Analyse::class);
    }
}
