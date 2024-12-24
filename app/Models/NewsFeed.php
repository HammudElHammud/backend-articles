<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'source_id',
        'author',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
