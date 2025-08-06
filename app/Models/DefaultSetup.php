<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultSetup extends Model
{
    protected $fillable = [
        'players',
        'schemes',
        'masterminds',
        'villains',
        'henchmen',
        'heroes',
        'twists',
        'bystanders',
        'wounds',
        'officers',
        'shards'
    ];
}
