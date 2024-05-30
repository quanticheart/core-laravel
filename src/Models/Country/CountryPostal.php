<?php


namespace App\Models\Country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * @method static where(string $string, string $string)
 * @property mixed $country_id
 * @property mixed|null $description
 * @property mixed|null $redundant_characters
 * @property mixed|null $regex
 * @property mixed|null $charset
 */
class CountryPostal extends Model
{
    use HasFactory, Notifiable;

    /**
     * @var string table name
     */
    protected $table = 'loc_country_postals';

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

    public function formats(): HasMany
    {
        return $this->hasMany(CountryPostalFormat::class, 'postal_id', 'id');
    }
}
