<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'prix',
        'tva',
        
    ];

    public function cheval()
    {
        return $this->hasMany(LivrePrestation::class);
    }
}
