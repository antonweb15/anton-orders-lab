<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'product', 'quantity', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Sort by newest first (default for API)
    public function scopeLatestFirst($query, bool $selectLimitedFields = false)
    {
        if ($selectLimitedFields) {
            $query->select(['id', 'name', 'price', 'product', 'quantity', 'status', 'created_at']);
        }
        return $query->orderBy('created_at', 'desc');
    }

    // Sort by oldest first
    public function scopeOldestFirst($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    // Sort by custom field & direction
    public function scopeSortBy($query, string $field, string $direction = 'asc')
    {
        return $query->orderBy($field, $direction);
    }

    // Sort by ID descending
    public function scopeIdDesc($query)
    {
        return $query->orderBy('id', 'desc');
    }

    // Filter by status
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // Filter by user
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Universal filter for API
    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->status($status))
            ->when($filters['user_id'] ?? null, fn ($q, $userId) => $q->forUser($userId))
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->whereDate('created_at', '<=', $to));
    }
}
