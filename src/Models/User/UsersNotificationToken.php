<?php

namespace Quanticheart\Laravel\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method whereNotNull(string $string)
 * @method static find($auth)
 * @method static delete()
 * @method static save()
 * @property mixed id
 * @property mixed user_id
 * @property mixed|string token
 */
class UsersNotificationToken extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @var string table name
     */
    protected $table = 'users_notification_token';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'token'
    ];

    /**
     * The attributes excluded from the model's JSON forms.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'updated_at', 'created_at'
    ];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public static function pushTokenList()
    {
        return (new UsersNotificationToken())->whereNotNull('token')->get(['id', 'token'])->toArray();
    }
}
