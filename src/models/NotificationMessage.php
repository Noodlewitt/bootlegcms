<?php

namespace Bootleg\Cms\Models;

use Eloquent;

class NotificationMessage extends Eloquent
{
    protected $table = 'notification_messages';
    protected $fillable = [
        'subject',
        'message',
        'from_id',
        'from_type',
    ];

    public function from()
    {
        return $this->morphTo();
    }

    public function getDisplayPictureAttribute()
    {
        $email = '';

        switch($this->from_type)
        {
            case 'store':
                $email = $this->from->contact_information->email;
                break;
            case 'user':
                $email = $this->from->email;
                break;
        }

        return 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).'jpg?d=identicon';
    }
}
