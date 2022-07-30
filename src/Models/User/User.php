<?php

namespace Quanticheart\Laravel\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method whereNotNull(string $string)
 * @method static find($id)
 * @method static delete()
 * @method static save()
 * @method static join(string $string, string $string1, string $string2, string $string3)
 * @method static whereActivate(int $int)
 * @property mixed id
 * @property mixed email
 * @property string password
 * @property mixed|string name
 * @property int|mixed level
 * @property bool|mixed activate
 * @property bool|mixed email_verified
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @var string table name
     */
    protected $table = 'users';

    /**
     * @var bool for block update timestamp updated_at and created_at
     */
//        public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'email_verified', 'email_verified_at', 'remember_token',
    ];

    /**
     * The attributes excluded from the model's JSON forms.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password', 'updated_at', 'created_at', 'level', 'activate'
    ];

    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) { // before delete() method call this
            $user->userData()->delete();
            $user->usersNotificationToken()->delete();
            // do the rest of the cleanup...
        });
    }

    public static function getUserData(int $id)
    {
        return User::join('users_data', 'users_data.user_id', '=', 'users.id')
            ->join('colors', 'users_data.color_id', '=', 'colors.id')
//            ->with('usersNotificationToken')
            ->get([
                'users.id',
                'users.email',
                'users.level',
                'users.activate',
                'users_data.name',
                'users_data.surname',
                'users_data.cell_phone',
                'colors.color_one',
                'colors.color_two'
            ])
            ->where('id', $id)
            ->first();
    }

    public function userData(): HasOne
    {
        return $this->hasOne(UsersData::class, 'user_id', 'id');
    }

    public function usersNotificationToken(): HasMany
    {
        return $this->hasMany(UsersNotificationToken::class, 'user_id', 'id');
    }

    public static function emailList()
    {
        return (new User)->whereNotNull('email')->pluck('email')->all();
    }

}
