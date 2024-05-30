<?php


namespace App\Models\Country;

use App\Models\Site\Color;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

/**
 * @property mixed|int $mac_address_id
 * @property mixed|string $data
 * @property int|mixed $imei_id
 * @property mixed|string $id_alpha
 * @property mixed|string $name
 * @property mixed|string $long_name
 * @property mixed|string $alpha_2
 * @property mixed|string $alpha_3
 * @property mixed|string $isoNumericCode
 * @property mixed|string $ioc
 * @property mixed|string $capital
 * @property mixed|string $tld
 * @property mixed $id
 * @property int|mixed $number_states
 * @method static where(string $string, string $alpha3)
 */
class Currencies extends Model
{
    use HasFactory, Notifiable;

    /**
     * @var string table name
     */
    protected $table = 'loc_currencies';

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
        'updated_at', 'created_at'
    ];

}
