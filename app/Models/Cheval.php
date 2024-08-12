<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheval extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'date_de_naissance',
        'photo',
        'poids',
    ];

    public function inscriptions()
{
    return $this->hasMany(Inscription::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function prestations()
    {
        return $this->hasMany(LivrePrestation::class);
    }
}
