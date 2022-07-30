<?php

namespace Quanticheart\Laravel\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationAndEmailUser extends Notification
{
    use Queueable;

    /**
     * in error :
     *
     * Type of Illuminate\Notifications\DatabaseNotification::$table must be string (as in class Illuminate\Database\Eloquent\Model)
     *
     * open file Illuminate\Notifications\DatabaseNotification
     * and change line
     *         protected $table = 'notifications';
     * to
     *         protected string $table = 'notifications';
     */

    /** @var user */
    public $user;

    /**
     * @param User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('Spacheship has launched!');
    }

    public function toArray($notifiable)
    {
        return [
            'msg' => 'OK, SPACEX!',
            'email' => $this->user->email
        ];
    }
}
