<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidate extends Model
{
    protected $guarded = [];

    public function setups(): BelongsTo
    {
        return $this->belongsTo(Setup::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }
}
