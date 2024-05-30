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
 * @property mixed $native_name
 * @property mixed $latitude
 * @property mixed $longitude
 * @property mixed $demonym
 * @property mixed $internal_id
 * @method static where(string $string, string $alpha3)
 */
class Countries extends Model
{
    use HasFactory, Notifiable;

    /**
     * @var string table name
     */
    protected $table = 'loc_countries';

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

    public function phone(): HasOne
    {
        return $this->hasOne(CountryPhone::class, 'country_id', 'id');
    }

    public function postal(): HasOne
    {
        return $this->hasOne(CountryPostal::class, 'country_id', 'id');
    }

    public function states(): HasMany
    {
        return $this->hasMany(CountryStates::class, 'country_id', 'id');
    }

    public function currencies(): HasMany
    {
        return $this->hasMany(CountryCurrency::class, 'country_id', 'id');
    }

    public function borders(): HasMany
    {
        return $this->hasMany(CountryBorder::class, 'country_id', 'id');
    }

    public function names(): hasOne
    {
        return $this->hasOne(CountryName::class, 'country_id', 'id');
    }

    public function timezone(): HasMany
    {
        return $this->hasMany(CountryTimezones::class, 'country_id', 'id');
    }

    public function continent(): hasOne
    {
        return $this->hasOne(Continents::class, 'id', 'continent_id');
    }

    public function locale(): hasOne
    {
        return $this->hasOne(Locale::class, 'id', 'locale_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CountryDocumentsTypes::class, 'country_id', 'id');
    }
}
