<?php

namespace Quanticheart\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property mixed|string name
 * @property mixed|string color_one
 * @property mixed|string color_two
 * @property mixed id
 * @method static find($color_id)
 */
class Color extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'color_one', 'color_two', 'activate'
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
