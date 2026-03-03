<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $fillable = [
        'chat_id',
        'admin_id',
        'is_from_user',
        'text',
        'photo_url',
    ];

    public function chat()
    {
        return $this->belongsTo(SupportChat::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
