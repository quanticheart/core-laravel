<?php

namespace Quanticheart\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @method whereNotNull(string $string)
 * @method static find($id)
 * @method static delete()
 * @method static save()
 * @method static join(string $string, string $string1, string $string2, string $string3)
 * @property mixed|string title
 * @property mixed|string description
 */
class SiteData extends Model
{
    use HasFactory, Notifiable;

    /**
     * @var string table name
     */
    protected $table = 'site_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'title', 'description', 'keywords', 'cell_phone_one',

        'cell_phone_two', 'email_adm', 'email_one', 'email_two',

        'logo', 'medium_logo', 'favicon',

        'facebook_page', 'youtube_channel', 'color_id', 'updated_at',
    ];

    /**
     * The attributes excluded from the model's JSON forms.
     *
     * @var array
     */
    protected $hidden = [
        'created_at'
    ];
}
