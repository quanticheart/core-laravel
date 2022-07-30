<?php

namespace Quanticheart\Laravel\Models\ApiToken;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property mixed|string $id
 * @property mixed|string $name
 * @property int|mixed $user_id
 * @property mixed|string $token
 * @property int|mixed $platform
 * @property int|mixed $active
 * @method static where(string $string, array|string $apiToken)
 */
class ApiToken extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes excluded from the model's JSON forms.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function platformDetails(): HasOne
    {
        return $this->hasOne(ApiTokenPlatform::class, 'id', 'platform');
    }
}
