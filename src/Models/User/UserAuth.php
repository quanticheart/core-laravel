<?php

namespace Quanticheart\Laravel\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property mixed|int $user_id
 * @property mixed|string $token
 * @property mixed|bool $active
 */
class UserAuth extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users_auth';

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
