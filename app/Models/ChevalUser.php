<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChevalUser extends Pivot
{
    protected $fillable = [
        'user_id',
        'cheval_id',
    ];
}
