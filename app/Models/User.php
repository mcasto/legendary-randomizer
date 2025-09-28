<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'data_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user settings for this user's data_id.
     */
    public function userSettings()
    {
        return $this->hasOne(UserSettings::class, 'user_data_id', 'data_id');
    }

    /**
     * Get the mastermind display settings for this user's data_id.
     */
    public function mastermindDisplay()
    {
        return $this->hasOne(MastermindDisplay::class, 'user_data_id', 'data_id');
    }

    /**
     * Get the scheme display settings for this user's data_id.
     */
    public function schemeDisplay()
    {
        return $this->hasOne(SchemeDisplay::class, 'user_data_id', 'data_id');
    }

    /**
     * Get the villain display settings for this user's data_id.
     */
    public function villainDisplay()
    {
        return $this->hasOne(VillainDisplay::class, 'user_data_id', 'data_id');
    }

    /**
     * Get the henchmen display settings for this user's data_id.
     */
    public function henchmenDisplay()
    {
        return $this->hasOne(HenchmenDisplay::class, 'user_data_id', 'data_id');
    }

    /**
     * Get the hero display settings for this user's data_id.
     */
    public function heroDisplay()
    {
        return $this->hasOne(HeroDisplay::class, 'user_data_id', 'data_id');
    }

    /**
     * Get the sets owned by this user's data_id.
     */
    public function userSets()
    {
        return $this->hasMany(HasSet::class, 'data_id', 'data_id');
    }

    /**
     * Get the sets owned by this user's data_id with full set information.
     */
    public function setsWithDetails()
    {
        return $this->userSets()->join('sets', 'has_sets.set_value', '=', 'sets.value')
            ->select('sets.id', 'sets.value', 'sets.label')
            ->orderBy('sets.label');
    }

    /**
     * Get all display settings for this user's data_id.
     */
    public function settings()
    {
        // Get all display settings
        $displays = [
            'masterminds' => $this->mastermindDisplay,
            'schemes' => $this->schemeDisplay,
            'villains' => $this->villainDisplay,
            'henchmen' => $this->henchmenDisplay,
            'heroes' => $this->heroDisplay,
        ];

        // Sort displays by their order field
        uasort($displays, function ($a, $b) {
            if ($a === null && $b === null) return 0;
            if ($a === null) return 1;
            if ($b === null) return -1;
            return $a->order <=> $b->order;
        });

        // Get user's sets with full details
        $sets = $this->setsWithDetails()->get()->toArray();

        return [
            'status' => 'success',
            'sets' => $sets,
            'settings' => $this->userSettings,
            'displays' => $displays,
        ];
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }
}
