<?php


namespace App\Models\Country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

/**
 * @method static where(string $string, string $string)
 * @property mixed $country_id
 * @property mixed $currency_id
 */
class CountryCurrency extends Model
{
    use HasFactory, Notifiable;

    /**
     * @var string table name
     */
    protected $table = 'loc_country_currency';

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

    public function details(): HasOne
    {
        return $this->hasOne(Currencies::class, 'id', 'currency_id');
    }
}
