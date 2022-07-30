<?php

namespace Quanticheart\Laravel\Models\ApiToken;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int|mixed $token_id
 * @property int|mixed $create_by
 * @property int|mixed $create_for
 */
class ApiTokenCreation extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @var string table name
     */
    protected $table = 'api_token_creations';

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
