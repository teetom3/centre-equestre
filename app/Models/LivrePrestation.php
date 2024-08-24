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
        'etat',
        'date_validation',
        'date_facturation',
        'date_paiement',
        'validated_by',
        'invoiced_by',
        'paid_by',
    ];

    public function cheval()
    {
        return $this->belongsTo(Cheval::class);
    }

    public function prestation()
    {
        return $this->belongsTo(Prestation::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function invoicer()
    {
        return $this->belongsTo(User::class, 'invoiced_by');
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}

