<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'user_id',
        'image',
        'publish_status',
    ];

    /**
     * Get the task belong to user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
