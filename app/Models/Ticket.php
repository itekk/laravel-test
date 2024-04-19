<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Ticket extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass-assigned.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'priority',
        'status',
    ];

    /**
     * Cast 'status' and 'priority' attributes to enums.
     */
    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
    ];

    /**
     * A ticket belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A ticket can have many responses.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    /**
     * Scope to filter tickets based on provided filters.
     *
     * @param Builder $query The Eloquent query builder
     * @param array $filters An array of filters to apply
     * @return Builder The query builder with filters applied
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        // Early return if no filters are provided
        if (empty($filters)) {
            return $query;
        }

        // Destructure filters for easier access
        $title = $filters['title'] ?? null;
        $content = $filters['content'] ?? null;
        $username = $filters['username'] ?? null;
        $email = $filters['email'] ?? null;
        $dateRange = $filters['dateRange'] ?? [null, null];
        $priority = $filters['priority'] ?? null;
        $status = $filters['status'] ?? null;

        // Filter by title
        if ($title) {
            $query->where('title', 'like', "%$title%");
        }

        // Filter by content
        if ($content) {
            $query->where('description', 'like', "%$content%");
        }

        // Filter by username
        if ($username) {
            $query->whereHas('user', function ($q) use ($username) {
                $q->where('name', 'like', "%$username%");
            });
        }

        // Filter by email
        if ($email) {
            $query->whereHas('user', function ($q) use ($email) {
                $q->where('email', 'like', "%$email%");
            });
        }

        // Filter by date range
        if ($dateRange[0] || $dateRange[1]) {
            $startDate = $dateRange[0] ? Carbon::parse($dateRange[0]) : null;
            $endDate = $dateRange[1] ? Carbon::parse($dateRange[1]) : Carbon::now();

            // Add one day to the end date
            if ($endDate) {
                $endDate->addDay();
            }

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Filter by priority
        if ($priority) {
            $query->where('priority', $priority);
        }

        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }

        return $query;
    }
}
