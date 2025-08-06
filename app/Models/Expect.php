<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expect extends Model
{
    protected $guarded = [];

    public function setups(): BelongsTo
    {
        return $this->belongsTo(Setup::class);
    }
}
