<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivrePrestation extends Model
{
    use HasFactory;

    protected $table = 'livre_des_prestations';


    protected $fillable = [
        'cheval_id',
        'prestation_id',
        'date_prestation',
    ];

    public function cheval()
    {
        return $this->belongsTo(Cheval::class);
    }

    public function prestation()
    {
        return $this->belongsTo(Prestation::class);
    }
}
