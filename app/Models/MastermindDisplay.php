<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MastermindDisplay extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_data_id',
        'bg',
        'text',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    /**
     * Get the users that belong to this data_id.
     * Note: Multiple users can share the same data_id.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'data_id', 'user_data_id');
    }

    /**
     * Get a user that belongs to this data_id (convenience method).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_data_id', 'data_id');
    }
}
