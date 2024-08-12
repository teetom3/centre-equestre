<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'nombre_de_place',
        'image_de_presentation',
    ];

    public function inscriptions()
{
    return $this->hasMany(Inscription::class);
}

}
