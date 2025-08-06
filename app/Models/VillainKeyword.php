<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillainKeyword extends Model
{
    // Relationships
    public function villain(): BelongsTo
    {
        return $this->belongsTo(Villain::class, 'villain_id');
    }

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(Keyword::class, 'keyword_id');
    }
}
