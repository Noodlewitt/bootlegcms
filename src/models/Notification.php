<?php

namespace Bootleg\Cms\Models;

use Application;
use Eloquent;
use Illuminate\Support\Facades\Auth;

class Notification extends Eloquent
{
    protected $table = 'notifications';
    protected $fillable = [
        'message_id',
        'to_id',
        'to_type',
        'status',
    ];

    const STATUS_READ = 1;
    const STATUS_UNREAD = 0;

    public function message()
    {
        return $this->hasOne(NotificationMessage::class, 'id', 'message_id');
    }

    public function to()
    {
        return $this->morphTo();
    }

    public function scopeUnread($q)
    {
        return $q->whereStatus(static::STATUS_UNREAD);
    }

    public function scopeToApplication($q, $id = null)
    {
        if($id === null) $id = @Application::getApplication()->id;

        if($id === null) return $q->where('to_type', 'invalid');

        return $q->where(function($sq) use ($id) {
            $sq->where('to_type', 'application')->where('to_id', $id);
        });
    }

    public function scopeToStore($q, $id = null)
    {
        try {
            if($id === null) $id = @app('CurrentStore')->id;

            if($id === null) return $q->where('to_type', 'invalid');

            return $q->where(function($sq) use ($id) {
                $sq->where('to_type', 'store')->where('to_id', $id);
            });
        } catch (\Exception $e) {
            return $q->where('to_type', 'invalid');
        }
    }

    public function scopeToUser($q, $id = null)
    {
        if($id === null) $id = @Auth::user()->id;

        if($id === null) return $q->where('to_type', 'invalid');

        return $q->where(function($sq) use ($id) {
            $sq->where('to_type', 'user')->where('to_id', $id);
        });
    }

    public function scopeToCurrent($q)
    {
        $ids = [
            'application' => @Application::getApplication()->id,
            'store' => @app('CurrentStore')->id,
            'user' => @Auth::user()->id,
        ];

        return $q->where(function($sq) use ($ids){
            foreach($ids as $type => $id) {
                $sq->orWhere(function($ssq) use ($type, $id) {
                    if($id === null) $type = 'invalid';
                    $ssq->where('to_type', $type)->where('to_id', $id);
                });
            }
        });
    }

    public function isUnread()
    {
        return $this->status == static::STATUS_UNREAD;
    }
}
