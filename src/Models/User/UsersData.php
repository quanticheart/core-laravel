<?php

namespace Quanticheart\Laravel\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Quanticheart\Laravel\Models\Color;

/**
 * @method whereNotNull(string $string)
 * @method static find($auth)
 * @method static delete()
 * @method static save()
 * @method static join(string $string, string $string1, string $string2, string $string3)
 * @method static whereUserId(mixed $auth)
 * @property mixed id
 * @property mixed email
 * @property string password
 * @property mixed|string name
 * @property mixed user_id
 * @property mixed color_id
 * @property mixed|string cell_phone
 * @property mixed|string surname
 */
class UsersData extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'cell_phone', 'color_id',
    ];

    /**
     * The attributes excluded from the model's JSON forms.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'updated_at', 'created_at'
    ];

    public function color(): HasOne
    {
        return $this->hasOne(Color::class, 'id', 'color_id');
    }
}
