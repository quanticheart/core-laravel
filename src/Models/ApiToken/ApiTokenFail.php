<?php

namespace Quanticheart\Laravel\Models\ApiToken;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property mixed|string $name
 * @property mixed|string|null $ip
 * @property mixed $token
 * @property false|mixed|string $others
 * @property mixed $type
 */
class ApiTokenFail extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @var string table name
     */
    protected $table = 'api_tokens_fail_connection';

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
}
