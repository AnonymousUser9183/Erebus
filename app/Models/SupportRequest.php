<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * SupportRequest Model
 * =========================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class SupportRequest extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'subject',
        'title',
        'status',
        'ticket_id',
        'message',
        'category',
        'is_admin_reply',
        'parent_id'
    ];

    protected $appends = [
        'formatted_message'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            // Generate UUID if not set
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            
            // Generate ticket_id only for main support requests
            if (empty($model->parent_id) && empty($model->ticket_id)) {
                $model->ticket_id = self::generateUniqueTicketId();
            }

            // Sanitize message content if present
            if (!empty($model->message)) {
                $model->message = self::sanitizeMessage($model->message);
            }
        });

        static::updating(function (Model $model) {
            // Also sanitize message on updates
            if (!empty($model->message)) {
                $model->message = self::sanitizeMessage($model->message);
            }
        });
    }

    /**
     * User who created the support request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Messages/replies to this support request
     * Uses the old system where replies are SupportRequest records with parent_id
     */
    public function messages()
    {
        return $this->hasMany(SupportRequest::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Replies to this support request (alias for messages, for compatibility with new code)
     */
    public function replies()
    {
        return $this->hasMany(SupportRequest::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Parent support request (if this is a reply)
     */
    public function parentRequest()
    {
        return $this->belongsTo(SupportRequest::class, 'parent_id');
    }

    /**
     * Latest message/reply to this support request
     */
    public function latestMessage()
    {
        return $this->hasOne(SupportRequest::class, 'parent_id')->latest();
    }

    /**
     * Get count of replies
     */
    public function getRepliesCountAttribute()
    {
        return $this->messages()->count();
    }

    /**
     * Sanitize the message content to prevent XSS and other injection attacks
     *
     * @param string $message
     * @return string
     */
    private static function sanitizeMessage(string $message): string
    {
        // Remove HTML and PHP tags
        $message = strip_tags($message);
        
        // Convert special characters to HTML entities
        $message = htmlspecialchars($message, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Remove any null bytes
        $message = str_replace(chr(0), '', $message);
        
        // Remove any potentially dangerous Unicode characters
        $message = preg_replace('/[^\P{C}\n]+/u', '', $message);
        
        // Normalize line endings
        $message = str_replace(["\r\n", "\r"], "\n", $message);
        
        // Remove multiple consecutive newlines
        $message = preg_replace("/\n{3,}/", "\n\n", $message);
        
        // Trim whitespace
        $message = trim($message);

        return $message;
    }

    /**
     * Get the sanitized message for display
     *
     * @return Illuminate\Support\HtmlString
     */
    public function getFormattedMessageAttribute(): HtmlString
    {
        // Convert newlines to <br> tags for display while maintaining security
        $message = nl2br($this->message);
        return new HtmlString($message);
    }

    public static function generateUniqueTicketId()
    {
        do {
            $ticketId = Str::random(30);
        } while (static::where('ticket_id', $ticketId)->exists());

        return $ticketId;
    }

    public function getRouteKeyName()
    {
        return 'ticket_id';
    }

    /**
     * Scope a query to only include main support requests (not messages)
     */
    public function scopeMainRequests($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Check if this is a message (has a parent)
     */
    public function isMessage(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Check if this is a main support request
     */
    public function isMainRequest(): bool
    {
        return is_null($this->parent_id);
    }
}
