<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * ReturnAddress Model
 * =========================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'monero_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
