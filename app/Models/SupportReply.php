<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * SupportReply Model
 * =========================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'support_request_id',
        'user_id',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship to support request
    public function supportRequest()
    {
        return $this->belongsTo(SupportRequest::class, 'support_request_id', 'id');
    }

    // Relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
